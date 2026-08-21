<?php

return [
    'default_type' => env('VERIFICATION_DEFAULT_TYPE', 'otp'), // 'otp' | 'token'
    'otp_digits' => (int) env('VERIFICATION_OTP_DIGITS', 6),
    'token_length' => (int) env('VERIFICATION_TOKEN_LENGTH', 64),
    'otp_expiry_minutes' => (int) env('VERIFICATION_OTP_EXPIRY_MINUTES', 10),
    'token_expiry_minutes' => (int) env('VERIFICATION_TOKEN_EXPIRY_MINUTES', 60),
    'resend_cooldown_seconds' => (int) env('VERIFICATION_RESEND_COOLDOWN', 60),
    'max_resend_requests' => (int) env('VERIFICATION_MAX_RESENDS', 5),
    'max_attempts' => (int) env('VERIFICATION_MAX_ATTEMPTS', 5),
    'block_hours' => (int) env('VERIFICATION_BLOCK_HOURS', 24),
    'success_redirect_url' => env('VERIFICATION_SUCCESS_REDIRECT', '/verification/success'),
    'failed_redirect_url' => env('VERIFICATION_FAILED_REDIRECT', '/verification/failed'),
];
