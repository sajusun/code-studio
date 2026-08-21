@props([
    'appName' => null,
])

@php
    $name = $appName ?? config('app.name', 'Master Admin');
@endphp

<tr>
    <td style="padding: 0 40px;">
        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
            <tr>
                <td style="border-top: 1px solid #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td class="email-footer-cell" align="center" style="padding: 24px 40px 32px; text-align: center;">
        <p style="margin: 0 0 6px; color: #94a3b8; font-family: 'Inter', Arial, sans-serif; font-size: 12px; line-height: 1.6;">
            This is an automated message from <strong style="color: #475569;">{{ $name }}</strong>.
        </p>
        <p style="margin: 0; color: #cbd5e1; font-family: 'Inter', Arial, sans-serif; font-size: 11px; line-height: 1.5;">
            &copy; {{ date('Y') }} {{ $name }}. All rights reserved.
        </p>
        @if (isset($slot) && $slot->isNotEmpty())
            <div style="margin-top: 12px; color: #94a3b8; font-family: 'Inter', Arial, sans-serif; font-size: 11px;">
                {{ $slot }}
            </div>
        @endif
    </td>
</tr>
