<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected VerificationService $verificationService
    ) {}

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'purpose' => 'required|string|in:email_verification,password_reset,phone_verification',
            'type' => 'nullable|string|in:otp,token',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $verification = $this->verificationService->send($user, $validated['purpose'], $validated['type'] ?? null);

        return self::success('Verification code sent successfully', [
            'purpose' => $verification->purpose,
            'type' => $verification->verification_type,
            'expires_at' => $verification->expires_at,
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'purpose' => 'required|string',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $verified = $this->verificationService->verifyOtp($user, $validated['purpose'], $validated['code']);

        if (!$verified) {
            return self::error('Invalid verification OTP code.', 422);
        }

        if ($validated['purpose'] === 'email_verification') {
            $user->markEmailAsVerified();
        }

        return self::success('Verification successful.', [
            'email' => $user->email,
            'verified' => true,
        ]);
    }

    public function verifyToken(Request $request): RedirectResponse
    {
        $purpose = $request->query('purpose', 'email_verification');
        $token = $request->query('token', '');

        $redirectUrl = $this->verificationService->verifyToken($purpose, $token);
        return redirect()->away($redirectUrl);
    }

    public function resend(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'purpose' => 'required|string',
            'type' => 'nullable|string|in:otp,token',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();
        $verification = $this->verificationService->resend($user, $validated['purpose'], $validated['type'] ?? null);

        return self::success('Verification code resent successfully', [
            'purpose' => $verification->purpose,
            'expires_at' => $verification->expires_at,
        ]);
    }
}
