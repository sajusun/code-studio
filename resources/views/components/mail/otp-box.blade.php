@props([
    'code'          => '',
    'expiryMinutes' => null,
    'label'         => 'Your One-Time Password',
])

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
    <tr>
        <td align="center" style="background-color: #ecfdf5; border: 2px dashed #a7f3d0; border-radius: 14px; padding: 24px 20px; text-align: center;">
            <p style="margin: 0 0 8px; color: #047857; font-family: 'Inter', Arial, sans-serif; font-size: 11px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">
                {{ $label }}
            </p>

            <p class="otp-code" style="margin: 0; color: #065f46; font-family: 'Courier New', Courier, monospace; font-size: 40px; font-weight: 900; letter-spacing: 8px;">
                {{ $code }}
            </p>

            @if ($expiryMinutes !== null)
                <p style="margin: 10px 0 0; color: #059669; font-family: 'Inter', Arial, sans-serif; font-size: 12px;">
                    &#x23F1; Expires in <strong>{{ $expiryMinutes }} minute{{ $expiryMinutes != 1 ? 's' : '' }}</strong>
                </p>
            @endif
        </td>
    </tr>
</table>
