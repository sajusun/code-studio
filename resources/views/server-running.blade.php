<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Status</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background:
                radial-gradient(1200px 800px at 15% 15%, rgba(197, 164, 92, 0.14), rgba(197, 164, 92, 0) 60%),
                radial-gradient(900px 700px at 85% 75%, rgba(56, 189, 248, 0.08), rgba(56, 189, 248, 0) 55%),
                linear-gradient(180deg, #0b1220 0%, #070b14 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #0b1220;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        }

        .card {
            width: min(560px, 100%);
            background: linear-gradient(180deg, #ffffff 0%, #fbfbf8 100%);
            padding: 44px 48px;
            border-radius: 18px;
            border: 1px solid rgba(15, 23, 42, 0.12);
            box-shadow:
                0 30px 55px -28px rgba(2, 6, 23, 0.55),
                0 10px 30px -18px rgba(2, 6, 23, 0.35);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(115deg, rgba(197, 164, 92, 0.16), rgba(197, 164, 92, 0) 40%),
                linear-gradient(245deg, rgba(2, 6, 23, 0.06), rgba(2, 6, 23, 0) 55%);
            pointer-events: none;
        }

        .status-symbol {
            font-size: 2.6rem;
            margin-bottom: 14px;
            display: inline-block;
            filter: drop-shadow(0 10px 18px rgba(2, 6, 23, 0.18));
        }

        h1 {
            font-size: 2.25rem;
            font-weight: 650;
            color: #0b1220;
            letter-spacing: -0.4px;
            margin-bottom: 14px;
            font-family: ui-serif, Georgia, "Times New Roman", Times, serif;
        }

        .alive-indicator {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(2, 6, 23, 0.04);
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid rgba(2, 6, 23, 0.10);
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f5132;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            position: relative;
            z-index: 1;
        }

        .pulse-dot {
            width: 12px;
            height: 12px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
            animation: pulse 1.6s ease-in-out infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.92);
                opacity: 0.65;
            }

            50% {
                transform: scale(1.12);
                opacity: 1;
                background-color: #16a34a;
                box-shadow: 0 0 0 7px rgba(22, 163, 74, 0.16);
            }

            100% {
                transform: scale(0.92);
                opacity: 0.65;
            }
        }

        .blink-text {
            animation: softBlink 2.2s infinite;
            letter-spacing: 0.12em;
        }

        @keyframes softBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        hr {
            margin: 18px 0 16px;
            border: none;
            height: 1px;
            background: linear-gradient(90deg, rgba(2, 6, 23, 0), rgba(2, 6, 23, 0.18), rgba(2, 6, 23, 0));
            position: relative;
            z-index: 1;
        }

        .sub {
            font-size: 0.78rem;
            color: rgba(2, 6, 23, 0.62);
            margin-top: 6px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            letter-spacing: 0.08em;
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="status-symbol">
            ⚙️
        </div>
        <h1>Server is Running</h1>
        <div class="alive-indicator">
            <div class="pulse-dot"></div>
            <span class="blink-text">● ALIVE / ACTIVE</span>
        </div>
        <hr>
        <div class="sub">
            [ STILL ALIVE — SYSTEM OPERATIONAL ]
        </div>
    </div>
</body>

</html>
