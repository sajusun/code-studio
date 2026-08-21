<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket Reply</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 32px 16px;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #4f46e5;
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 14px;
            color: #c7d2fe;
        }
        .content {
            padding: 32px 24px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .message-body {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            white-space: pre-wrap;
            margin-bottom: 32px;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 32px 0;
        }
        .history-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 16px;
        }
        .history-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .history-item {
            padding: 16px;
            border-radius: 8px;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            margin-bottom: 12px;
        }
        .history-meta {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .history-meta .sender {
            font-weight: 600;
            color: #475569;
        }
        .history-meta .time {
            float: right;
            font-size: 11px;
            color: #94a3b8;
        }
        .history-body {
            font-size: 14px;
            line-height: 1.5;
            color: #334155;
            white-space: pre-wrap;
            clear: both;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    @php
        $siteSettings = \App\Models\Setting::query()->where('key', 'site')->first()?->value ?? [];
        $siteName = $siteSettings['name'] ?? 'Hotel Management System';
        $supportEmail = $siteSettings['support_email'] ?? 'support@example.com';
        $supportPhone = $siteSettings['support_phone'] ?? '';
    @endphp
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>{{ $siteName }}</h1>
                <p>Support Ticket #{{ $conversation->id }} &bull; {{ $conversation->subject }}</p>
            </div>
            <div class="content">
                <div class="greeting">Hello {{ $conversation->customer_name ?: 'Valued Guest' }},</div>
                <div class="message-body">{{ $replyMessage->body }}</div>

                @if($conversation->messages->count() > 1)
                    <div class="divider"></div>
                    <div class="history-title">Conversation History</div>
                    <div class="history-list">
                        @foreach($conversation->messages->sortByDesc('id') as $msg)
                            @if($msg->id !== $replyMessage->id)
                                <div class="history-item">
                                    <div class="history-meta">
                                        <span class="sender">
                                            @if($msg->direction === 'in')
                                                {{ $conversation->customer_name ?: $conversation->customer_email }}
                                            @else
                                                {{ $siteName }} Support
                                            @endif
                                        </span>
                                        <span class="time">{{ $msg->created_at?->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="history-body">{{ $msg->body }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="footer">
                <p>Have more questions? Reply directly to this email or contact us.</p>
                <p>
                    <strong>Email:</strong> <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                    @if($supportPhone)
                        &nbsp;|&nbsp; <strong>Phone:</strong> {{ $supportPhone }}
                    @endif
                </p>
                <p style="margin-top: 16px; font-size: 11px; color: #94a3b8;">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
