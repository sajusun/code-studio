<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Notifications\NewNewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request): array
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = max(1, min(100, $perPage));

        $q = trim((string) $request->query('q', ''));

        $paginator = NewsletterSubscriber::query()
            ->when($q !== '', fn ($qb) => $qb->where('email', 'like', "%{$q}%"))
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return $paginator->toArray();
    }

    // Public endpoint (for frontend site email capture)
    public function store(Request $request): array
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('newsletter_subscribers', 'email'),
            ],
        ]);

        $subscriber = NewsletterSubscriber::query()->create([
            'email' => strtolower($validated['email']),
        ]);

        $admins = User::whereIn('role', ['admin', 'help_desk'])->get();
        Notification::send($admins, new NewNewsletterSubscriber($subscriber));

        return [
            'data' => [
                'id' => $subscriber->id,
                'email' => $subscriber->email,
                'created_at' => $subscriber->created_at?->toIso8601String(),
            ],
        ];
    }
}

