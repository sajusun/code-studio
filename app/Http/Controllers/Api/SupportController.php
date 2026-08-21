<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportConversation;
use App\Models\SupportMessage;
use App\Models\User;
use App\Mail\SupportReplyMail;
use App\Notifications\NewSupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    public function index(Request $request): array
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q', ''));

        $paginator = SupportConversation::query()
            ->when($status !== '', fn ($qb) => $qb->where('status', $status))
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($sub) use ($q) {
                    $sub->where('subject', 'like', "%{$q}%")
                        ->orWhere('customer_email', 'like', "%{$q}%")
                        ->orWhere('customer_name', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return $paginator->toArray();
    }

    public function show(int $conversation): array
    {
        $conv = SupportConversation::query()
            ->with(['messages' => fn ($q) => $q->orderBy('id')])
            ->findOrFail($conversation);

        return [
            'data' => [
                'id' => $conv->id,
                'subject' => $conv->subject,
                'status' => $conv->status,
                'customer_email' => $conv->customer_email,
                'customer_name' => $conv->customer_name,
                'last_message_at' => $conv->last_message_at?->toIso8601String(),
                'created_at' => $conv->created_at?->toIso8601String(),
                'messages' => $conv->messages->map(fn (SupportMessage $m) => [
                    'id' => $m->id,
                    'direction' => $m->direction,
                    'from_email' => $m->from_email,
                    'to_email' => $m->to_email,
                    'subject' => $m->subject,
                    'body' => $m->body,
                    'provider' => $m->provider,
                    'provider_message_id' => $m->provider_message_id,
                    'sent_at' => $m->sent_at?->toIso8601String(),
                    'received_at' => $m->received_at?->toIso8601String(),
                    'created_at' => $m->created_at?->toIso8601String(),
                ]),
            ],
        ];
    }

    public function createConversation(Request $request): array
    {
        $validated = $request->validate([
            'customer_email' => ['required', 'email', 'max:190'],
            'customer_name' => ['nullable', 'string', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $conv = SupportConversation::query()->create([
            'subject' => $validated['subject'] ?? null,
            'status' => 'open',
            'customer_email' => strtolower($validated['customer_email']),
            'customer_name' => $validated['customer_name'] ?? null,
            'last_message_at' => now(),
        ]);

        SupportMessage::query()->create([
            'conversation_id' => $conv->id,
            'direction' => 'in',
            'from_email' => $conv->customer_email,
            'to_email' => null,
            'subject' => $conv->subject,
            'body' => $validated['message'],
            'received_at' => now(),
        ]);

        $admins = User::whereIn('role', ['admin', 'help_desk'])->get();
        Notification::send($admins, new NewSupportTicket($conv));

        return [
            'data' => [
                'id' => $conv->id,
            ],
        ];
    }

    public function reply(Request $request, int $conversation): array
    {
        $conv = SupportConversation::query()->findOrFail($conversation);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'to_email' => ['nullable', 'email', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
        ]);

        $to = $validated['to_email'] ?? $conv->customer_email;

        $msg = SupportMessage::query()->create([
            'conversation_id' => $conv->id,
            'user_id' => $request->user()?->id,
            'direction' => 'out',
            'from_email' => null,
            'to_email' => $to,
            'subject' => $validated['subject'] ?? $conv->subject,
            'body' => $validated['message'],
            'provider' => 'manual',
            'sent_at' => now(),
        ]);

        $conv->update(['last_message_at' => now()]);

        $siteSettings = \App\Models\Setting::query()->where('key', 'site')->first()?->value ?? [];
        $fromEmail = $siteSettings['support_email'] ?? config('mail.from.address');
        $fromName = $siteSettings['name'] ?? config('mail.from.name');

        try {
            Mail::to($to)->send(
                (new SupportReplyMail($conv, $msg))
                    ->from($fromEmail, $fromName)
            );
            $msg->update([
                'provider' => config('mail.default') ?: 'smtp',
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to send support reply email for conversation {$conv->id}: " . $e->getMessage(), [
                'exception' => $e
            ]);
        }

        return [
            'data' => [
                'id' => $msg->id,
            ],
        ];
    }

    public function updateStatus(Request $request, int $conversation): array
    {
        $conv = SupportConversation::query()->findOrFail($conversation);

        $validated = $request->validate([
            'status' => ['required', 'in:open,pending,closed'],
        ]);

        $conv->update(['status' => $validated['status']]);

        return [
            'data' => [
                'id' => $conv->id,
                'status' => $conv->status,
            ],
        ];
    }
}

