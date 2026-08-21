@props([
    'icon'     => '&#9993;',
    'title'    => '',
    'subtitle' => null,
])

<tr>
    <td class="email-header" align="center" style="background-color: #0f172a; border-bottom: 3px solid #10b981; padding: 36px 40px 28px; text-align: center;">
        <div style="display: inline-block; width: 56px; height: 56px; background-color: rgba(255,255,255,0.1); border-radius: 14px; font-size: 24px; line-height: 56px; text-align: center; margin-bottom: 14px; color: #ffffff;">
            {!! $icon !!}
        </div>

        <h1 style="margin: 0; color: #ffffff; font-family: 'Inter', Arial, sans-serif; font-size: 20px; font-weight: 800; line-height: 1.3; letter-spacing: -0.3px;">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p style="margin: 8px 0 0; color: #94a3b8; font-family: 'Inter', Arial, sans-serif; font-size: 13px; line-height: 1.5;">
                {{ $subtitle }}
            </p>
        @endif
    </td>
</tr>
