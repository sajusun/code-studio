@props([
    'url'      => '#',
    'label'    => 'Click Here',
    'color'    => '#10b981',
    'colorEnd' => '#059669',
    'align'    => 'center',
])

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 24px;">
    <tr>
        <td align="{{ $align }}">
            <!--[if mso]>
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $url }}" style="height: 50px; v-text-anchor: middle; width: 220px;" arcsize="20%" fill="t" stroke="f">
                <v:fill type="gradient" color="{{ $color }}" color2="{{ $colorEnd }}" angle="135" />
                <w:anchorlock/>
                <center style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; font-weight: 700;">
                    {{ $label }}
                </center>
            </v:roundrect>
            <![endif]-->
            <!--[if !mso]><!-->
            <a href="{{ $url }}" class="btn-cta" style="display: inline-block; background-color: {{ $color }}; background: linear-gradient(135deg, {{ $color }} 0%, {{ $colorEnd }} 100%); color: #ffffff; font-family: 'Inter', Arial, sans-serif; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 32px; border-radius: 12px; letter-spacing: 0.3px; line-height: 1;">
                {{ $label }}
            </a>
            <!--<![endif]-->
        </td>
    </tr>
</table>
