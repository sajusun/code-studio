<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broadcast Console Tester — Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0b0f19;
            color: #e2e8f0;
            padding: 24px;
        }

        /* Custom Thin Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #0d1117; }
        ::-webkit-scrollbar-thumb { background: #21262d; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #38bdf8; }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #f1f5f9;
        }

        .page-header a {
            font-size: 0.8rem;
            color: #38bdf8;
            text-decoration: none;
            padding: 5px 12px;
            border: 1px solid #1f293d;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .page-header a:hover { background: #1e3a5f; }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .container { grid-template-columns: 1fr; }
        }

        .card {
            background: #111827;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #1f293d;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
        }

        h2 {
            font-size: 1.05rem;
            margin-bottom: 16px;
            color: #38bdf8;
            border-bottom: 1px solid #1f293d;
            padding-bottom: 8px;
            font-weight: 600;
        }

        .form-group { margin-bottom: 14px; }

        label {
            display: block;
            font-size: 0.8rem;
            margin-bottom: 6px;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            background: #0b0f19;
            border: 1px solid #1f293d;
            border-radius: 8px;
            color: #f3f4f6;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus, select:focus {
            border-color: #38bdf8;
            box-shadow: 0 0 0 2px rgba(56,189,248,0.15);
        }

        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-primary { background: #0284c7; color: #fff; }
        .btn-primary:hover { background: #0369a1; }

        .btn-success { background: #059669; color: #fff; margin-top: 10px; }
        .btn-success:hover { background: #047857; }

        .btn-warning { background: #d97706; color: #fff; margin-top: 10px; }
        .btn-warning:hover { background: #b45309; }

        .btn-danger { background: #dc2626; color: #fff; margin-top: 8px; }
        .btn-danger:hover { background: #b91c1c; }

        .separator {
            border: none;
            border-top: 1px solid #1f293d;
            margin: 20px 0;
        }

        /* Quick Fire Section */
        .quick-fire-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .quick-fire-btn {
            padding: 8px;
            font-size: 0.78rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            border: none;
            text-align: center;
            transition: all 0.2s;
        }

        .qf-public { background: #1e3a5f; color: #38bdf8; }
        .qf-public:hover { background: #1d4ed8; color: #fff; }
        .qf-private { background: #2d1f3d; color: #c084fc; }
        .qf-private:hover { background: #7c3aed; color: #fff; }

        .channel-list {
            margin-top: 15px;
            max-height: 160px;
            overflow-y: auto;
        }

        .channel-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0b0f19;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.82rem;
            margin-bottom: 8px;
            border-left: 3px solid #38bdf8;
        }

        .channel-item.private { border-left-color: #f59e0b; }
        .channel-item.presence { border-left-color: #10b981; }

        .channel-item button {
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 0.8rem;
            padding: 2px 6px;
        }
        .channel-item button:hover { text-decoration: underline; }

        /* Terminal Console */
        .terminal-window {
            background: #0d1117;
            border-radius: 12px;
            border: 1px solid #21262d;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            height: 700px;
            overflow: hidden;
        }

        .terminal-header {
            background: #161b22;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #21262d;
        }

        .terminal-title {
            font-family: monospace;
            font-size: 0.82rem;
            color: #8b949e;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .terminal-toolbar {
            background: #161b22;
            padding: 6px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #21262d;
            gap: 12px;
        }

        .terminal-tabs { display: flex; gap: 4px; }

        .tab-btn {
            background: transparent;
            border: none;
            color: #8b949e;
            padding: 4px 10px;
            font-size: 0.78rem;
            border-radius: 6px;
            cursor: pointer;
            font-family: monospace;
        }

        .tab-btn.active {
            background: #21262d;
            color: #58a6ff;
            font-weight: bold;
        }

        .search-box {
            background: #0d1117;
            border: 1px solid #30363d;
            color: #c9d1d9;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.78rem;
            width: 180px;
            outline: none;
            font-family: monospace;
        }

        .terminal-body {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            font-family: 'JetBrains Mono', 'Fira Code', Consolas, Monaco, monospace;
            font-size: 0.83rem;
            line-height: 1.6;
            color: #c9d1d9;
        }

        .log-row {
            display: flex;
            gap: 10px;
            padding: 6px 8px;
            border-bottom: 1px solid #161b22;
            border-radius: 4px;
            transition: background 0.15s;
            word-break: break-word;
        }

        .log-row:hover { background: #161b22; }
        .log-row.type-error { background: rgba(248,81,73,0.1); border-color: rgba(248,81,73,0.2); }
        .log-row.type-event { background: rgba(163,113,247,0.08); }
        .log-row.type-success { background: rgba(46,160,67,0.08); }

        .log-time { color: #484f58; font-size: 0.75rem; user-select: none; min-width: 65px; }

        .log-badge {
            font-size: 0.7rem;
            padding: 1px 6px;
            border-radius: 4px;
            font-weight: bold;
            height: fit-content;
            text-transform: uppercase;
        }

        .badge-info { background: #1f6feb; color: #fff; }
        .badge-success { background: #238636; color: #fff; }
        .badge-error { background: #da3633; color: #fff; }
        .badge-event { background: #8957e5; color: #fff; }

        .log-content { flex: 1; }
        .log-msg { color: #e6edf3; font-weight: 500; }

        .json-tree {
            background: #161b22;
            padding: 10px;
            border-radius: 6px;
            margin-top: 6px;
            border: 1px solid #30363d;
            font-size: 0.8rem;
            overflow-x: auto;
        }
        .json-key { color: #79c0ff; }
        .json-string { color: #a5d6ff; }
        .json-number { color: #d2a8ff; }
        .json-boolean { color: #ff7b72; }
        .json-null { color: #ffa657; }

        .terminal-footer {
            background: #161b22;
            padding: 6px 16px;
            border-top: 1px solid #21262d;
            font-size: 0.75rem;
            color: #8b949e;
            display: flex;
            justify-content: space-between;
            font-family: monospace;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .status-dot.online { background: #3fb950; box-shadow: 0 0 8px #3fb950; }
        .status-dot.offline { background: #f85149; box-shadow: 0 0 8px #f85149; }
        .status-dot.connecting { background: #d29922; box-shadow: 0 0 8px #d29922; }
    </style>
</head>

<body>
    <div style="max-width: 1280px; margin: 0 auto;">
        <div class="page-header">
            <h1>🛰 Broadcast Console Tester</h1>
            <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
        </div>
    </div>

    <div class="container">

        <!-- Controls Panel -->
        <div class="card">
            <h2>Channel Config</h2>

            <div class="form-group">
                <label>Channel Type</label>
                <select id="channel_type">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="presence">Presence</option>
                </select>
            </div>

            <div class="form-group">
                <label>Channel Name</label>
                <input type="text" id="channel_name" placeholder="e.g. test-channel, App.Models.User.1">
            </div>

            <div class="form-group">
                <label>Event Name (Optional)</label>
                <input type="text" id="event_name" placeholder="e.g. .test-event or .NotificationSent">
            </div>

            <button class="btn btn-primary" id="subscribe_btn">
                <span>+</span> Subscribe Channel
            </button>

            <hr class="separator">

            <!-- Quick Fire Presets -->
            <h2 style="margin-bottom: 12px;">Quick Fire Presets</h2>
            <div class="quick-fire-row" style="margin-bottom: 8px;">
                <button class="quick-fire-btn qf-public" onclick="quickSubscribe('public', 'test-channel', '.test-event')">
                    📡 Public Test
                </button>
                <button class="quick-fire-btn qf-private" onclick="quickSubscribe('private', 'App.Models.User.{{ auth()->id() }}', '.NotificationSent')">
                    🔒 My Notifications
                </button>
            </div>
            <div class="quick-fire-row">
                <button class="quick-fire-btn qf-public" onclick="quickSubscribe('public', 'notifications', '.NotificationSent')">
                    🔔 Public Notifs
                </button>
                <button class="quick-fire-btn qf-private" onclick="quickSubscribe('private', 'App.Models.User.{{ auth()->id() }}', '')">
                    🔑 All User Events
                </button>
            </div>

            <hr class="separator">

            <!-- Fire Events -->
            <h2 style="margin-bottom: 12px;">Fire Events</h2>

            <div class="form-group">
                <label>Test Message</label>
                <input type="text" id="fire_message" value="Hello from Reverb! ✅" placeholder="Message to broadcast...">
            </div>

            <button class="btn btn-success" id="fire_public_btn">
                ⚡ Fire Public Broadcast
            </button>

            <button class="btn btn-success" id="fire_notif_btn" style="background: #7c3aed; margin-top: 8px;">
                🔔 Fire DB Notification
            </button>

            <hr class="separator">

            <h2 style="margin-bottom: 12px;">Active Channels</h2>
            <div class="channel-list" id="active_channels">
                <div style="color: #64748b; font-size: 0.85rem;" id="no_channels">No active channels</div>
            </div>

            <button class="btn btn-warning" id="clear_terminal_btn">Clear Logs</button>
            <button class="btn btn-danger" id="clear_cache_btn">Clear Cache & Unsubscribe All</button>
        </div>

        <!-- Terminal Console Output Window -->
        <div class="terminal-window">

            <!-- Window Header -->
            <div class="terminal-header">
                <div class="terminal-title">
                    <span id="ws-status-dot" class="status-dot connecting"></span>
                    <span id="ws-status-text">Connecting to Reverb...</span>
                </div>
                <div style="font-size: 0.75rem; color: #8b949e; font-family: monospace;">
                    User: {{ auth()->user()->name ?? 'Guest' }} (ID: {{ auth()->id() ?? 'N/A' }})
                </div>
            </div>

            <!-- Toolbar & Tabs -->
            <div class="terminal-toolbar">
                <div class="terminal-tabs">
                    <button class="tab-btn active" data-filter="all">All Logs</button>
                    <button class="tab-btn" data-filter="event">Events</button>
                    <button class="tab-btn" data-filter="error">Errors</button>
                    <button class="tab-btn" data-filter="info">System</button>
                    <button class="tab-btn" data-filter="success">Success</button>
                </div>
                <input type="text" class="search-box" id="log_search" placeholder="Filter logs...">
            </div>

            <!-- Output Body -->
            <div class="terminal-body" id="logs"></div>

            <!-- Footer Status Bar -->
            <div class="terminal-footer">
                <span id="ws-footer-status" style="color: #d29922; font-family: monospace;">
                    Connecting...
                </span>
                <span id="log_counter">0 logs shown</span>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const logsContainer     = document.getElementById('logs');
            const activeChannelsCon = document.getElementById('active_channels');
            const noChannelsMsg     = document.getElementById('no_channels');
            const searchInput       = document.getElementById('log_search');
            const logCounter        = document.getElementById('log_counter');
            const CSRF              = document.querySelector('meta[name="csrf-token"]').content;

            let activeSubscriptions = {};
            let storedLogs          = [];
            let currentFilter       = 'all';

            const STORAGE_CHANNELS_KEY = 'laravel_echo_channels_v4';
            const STORAGE_LOGS_KEY     = 'laravel_echo_logs_v4';

            // =====================================================================
            // WebSocket Connection Status
            // =====================================================================
            function setWsStatus(state) {
                const dot  = document.getElementById('ws-status-dot');
                const text = document.getElementById('ws-status-text');
                const foot = document.getElementById('ws-footer-status');

                const states = {
                    connected:    { cls: 'online',      label: 'Reverb Connected',    color: '#3fb950' },
                    disconnected: { cls: 'offline',     label: 'Disconnected',         color: '#f85149' },
                    connecting:   { cls: 'connecting',  label: 'Connecting to Reverb...', color: '#d29922' },
                };

                const s = states[state] || states.connecting;
                dot.className  = `status-dot ${s.cls}`;
                text.textContent = s.label;
                text.style.color = s.color;
                foot.textContent = s.label;
                foot.style.color = s.color;
            }

            if (typeof window.Echo !== 'undefined') {
                const conn = window.Echo.connector.pusher.connection;
                conn.bind('connected',    () => { setWsStatus('connected');    log('success', 'WebSocket connected to Laravel Reverb ✅'); });
                conn.bind('disconnected', () => { setWsStatus('disconnected'); log('error', 'WebSocket disconnected ❌'); });
                conn.bind('connecting',   () => setWsStatus('connecting'));

                if (conn.state === 'connected') {
                    setWsStatus('connected');
                    log('success', 'WebSocket already connected ✅');
                }
            } else {
                setWsStatus('disconnected');
                log('error', 'Laravel Echo is NOT loaded — run `npm run dev` or `npm run build`');
            }

            // =====================================================================
            // JSON Syntax Highlighter
            // =====================================================================
            function syntaxHighlight(json) {
                if (typeof json !== 'string') json = JSON.stringify(json, undefined, 2);
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/(\"(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*\"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                    let cls = 'json-number';
                    if (/^\"/.test(match)) {
                        cls = /:$/.test(match) ? 'json-key' : 'json-string';
                    } else if (/true|false/.test(match)) {
                        cls = 'json-boolean';
                    } else if (/null/.test(match)) {
                        cls = 'json-null';
                    }
                    return `<span class="${cls}">${match}</span>`;
                });
            }

            // =====================================================================
            // Local Storage
            // =====================================================================
            function saveStateToStorage() {
                const serializableSubs = {};
                Object.keys(activeSubscriptions).forEach(key => {
                    const sub = activeSubscriptions[key];
                    serializableSubs[key] = { type: sub.type, name: sub.name, eventName: sub.eventName };
                });
                localStorage.setItem(STORAGE_CHANNELS_KEY, JSON.stringify(serializableSubs));
                localStorage.setItem(STORAGE_LOGS_KEY, JSON.stringify(storedLogs));
            }

            function loadStateFromStorage() {
                const savedLogs = localStorage.getItem(STORAGE_LOGS_KEY);
                if (savedLogs) {
                    try { storedLogs = JSON.parse(savedLogs); renderAllLogs(); } catch (e) {}
                }

                const savedChannels = localStorage.getItem(STORAGE_CHANNELS_KEY);
                if (savedChannels) {
                    try {
                        const parsed = JSON.parse(savedChannels);
                        Object.keys(parsed).forEach(key => {
                            const sub = parsed[key];
                            subscribeToChannel(sub.type, sub.name, sub.eventName, true);
                        });
                    } catch (e) {}
                }
            }

            // =====================================================================
            // Logging Engine
            // =====================================================================
            function log(type, title, data = null) {
                const time = new Date().toLocaleTimeString('en-US', { hour12: false });
                const logItem = { type, title, data, time };

                storedLogs.push(logItem);
                if (storedLogs.length > 200) storedLogs.shift();

                renderAllLogs();
                saveStateToStorage();
            }

            function renderAllLogs() {
                logsContainer.innerHTML = '';
                const query = searchInput.value.toLowerCase();
                let count = 0;

                storedLogs.forEach(item => {
                    if (currentFilter !== 'all' && item.type !== currentFilter) return;
                    const searchStr = (item.title + ' ' + JSON.stringify(item.data || {})).toLowerCase();
                    if (query && !searchStr.includes(query)) return;
                    count++;
                    appendLogRowToUI(item);
                });

                logCounter.innerText = `${count} logs shown`;
                logsContainer.scrollTop = logsContainer.scrollHeight;
            }

            function appendLogRowToUI(item) {
                const row = document.createElement('div');
                row.className = `log-row type-${item.type}`;

                let badgeClass = 'badge-info';
                if (item.type === 'success') badgeClass = 'badge-success';
                if (item.type === 'error')   badgeClass = 'badge-error';
                if (item.type === 'event')   badgeClass = 'badge-event';

                const dataHTML = item.data
                    ? `<pre class="json-tree">${syntaxHighlight(item.data)}</pre>`
                    : '';

                row.innerHTML = `
                    <div class="log-time">${item.time}</div>
                    <div class="log-badge ${badgeClass}">${item.type}</div>
                    <div class="log-content">
                        <div class="log-msg">${item.title}</div>
                        ${dataHTML}
                    </div>
                `;
                logsContainer.appendChild(row);
            }

            // =====================================================================
            // Filter & Search
            // =====================================================================
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    currentFilter = e.target.getAttribute('data-filter');
                    renderAllLogs();
                });
            });

            searchInput.addEventListener('input', () => renderAllLogs());

            // =====================================================================
            // Actions
            // =====================================================================
            document.getElementById('clear_terminal_btn').onclick = () => {
                storedLogs = [];
                renderAllLogs();
                saveStateToStorage();
            };

            document.getElementById('clear_cache_btn').onclick = () => {
                if (!confirm('Clear all cached channels and logs?')) return;
                Object.keys(activeSubscriptions).forEach(key => {
                    if (typeof window.Echo !== 'undefined') Echo.leave(activeSubscriptions[key].name);
                });
                activeSubscriptions = {};
                storedLogs = [];
                localStorage.removeItem(STORAGE_CHANNELS_KEY);
                localStorage.removeItem(STORAGE_LOGS_KEY);
                renderAllLogs();
                renderActiveChannels();
                log('info', 'Local storage cache cleared.');
            };

            // =====================================================================
            // Quick Fire Fire Buttons
            // =====================================================================
            document.getElementById('fire_public_btn').onclick = async () => {
                const message = document.getElementById('fire_message').value || 'Test broadcast!';
                try {
                    const res = await fetch('{{ route('admin.broadcast-test.public') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ message })
                    });
                    const data = await res.json();
                    log(data.success ? 'success' : 'error', data.success ? '⚡ Public broadcast fired!' : '❌ Failed: ' + data.message, data.payload ?? null);
                } catch (e) {
                    log('error', '❌ HTTP Error: ' + e.message);
                }
            };

            document.getElementById('fire_notif_btn').onclick = async () => {
                const message = document.getElementById('fire_message').value || 'Test notification!';
                try {
                    const res = await fetch('{{ route('admin.broadcast-test.notification') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ title: '🔔 Test Notification', body: message, type: 'user' })
                    });
                    const data = await res.json();
                    log(data.success ? 'success' : 'error', data.success ? '🔔 Notification saved to DB + broadcast!' : '❌ ' + data.message, data.notification ?? null);
                } catch (e) {
                    log('error', '❌ HTTP Error: ' + e.message);
                }
            };

            // =====================================================================
            // Echo Subscriptions
            // =====================================================================
            function subscribeToChannel(type, channelName, eventName, isRestored = false) {
                if (typeof Echo === 'undefined') {
                    log('error', 'Laravel Echo is not loaded.');
                    return;
                }

                const channelKey = `${type}:${channelName}`;
                if (activeSubscriptions[channelKey]) {
                    if (!isRestored) log('error', `Already subscribed to ${type}: "${channelName}"`);
                    return;
                }

                log('info', `${isRestored ? '[Restored] ' : ''}Subscribing to ${type}: "${channelName}"...`);

                let instance = null;

                if (type === 'public') {
                    instance = Echo.channel(channelName);
                } else if (type === 'private') {
                    instance = Echo.private(channelName);
                } else if (type === 'presence') {
                    instance = Echo.join(channelName)
                        .here(users => log('info', `[Presence] Users in channel`, users))
                        .joining(user => log('info', `[Presence] User joined`, user))
                        .leaving(user => log('info', `[Presence] User left`, user));
                }

                if (!instance) return;

                instance
                    .subscribed(() => log('success', `✅ Subscribed to ${type}: "${channelName}"`))
                    .error(err  => log('error', `❌ Failed to subscribe to ${type}: "${channelName}"`, err));

                if (eventName) {
                    instance.listen(eventName, e => {
                        log('event', `📨 Event [${eventName}] on [${channelName}]`, e);
                    });
                } else {
                    instance.listenToAll((event, data) => {
                        log('event', `📨 Event [${event}] on [${channelName}]`, data);
                    });
                }

                activeSubscriptions[channelKey] = { instance, type, name: channelName, eventName };
                renderActiveChannels();
                saveStateToStorage();
            }

            document.getElementById('subscribe_btn').onclick = () => {
                const type        = document.getElementById('channel_type').value;
                const channelName = document.getElementById('channel_name').value.trim();
                const eventName   = document.getElementById('event_name').value.trim();

                if (!channelName) { alert('Please enter a Channel Name'); return; }
                subscribeToChannel(type, channelName, eventName);
            };

            // Quick subscribe helper (global so onclick works)
            window.quickSubscribe = (type, name, event) => {
                document.getElementById('channel_type').value = type;
                document.getElementById('channel_name').value = name;
                document.getElementById('event_name').value   = event;
                subscribeToChannel(type, name, event);
            };

            window.removeChannel = (key) => {
                if (activeSubscriptions[key]) {
                    const sub = activeSubscriptions[key];
                    if (typeof window.Echo !== 'undefined') Echo.leave(sub.name);
                    delete activeSubscriptions[key];
                    log('info', `Unsubscribed from ${sub.type}: "${sub.name}"`);
                    renderActiveChannels();
                    saveStateToStorage();
                }
            };

            function renderActiveChannels() {
                const keys = Object.keys(activeSubscriptions);
                if (keys.length === 0) {
                    noChannelsMsg.style.display = 'block';
                    activeChannelsCon.innerHTML = '';
                    activeChannelsCon.appendChild(noChannelsMsg);
                    return;
                }

                noChannelsMsg.style.display = 'none';
                activeChannelsCon.innerHTML = '';

                keys.forEach(key => {
                    const sub = activeSubscriptions[key];
                    const div = document.createElement('div');
                    div.className = `channel-item ${sub.type}`;
                    div.innerHTML = `
                        <span><strong>${sub.type.toUpperCase()}:</strong> ${sub.name}</span>
                        <button onclick="removeChannel('${key}')">Unsub</button>
                    `;
                    activeChannelsCon.appendChild(div);
                });
            }

            // Init
            loadStateFromStorage();
            log('info', 'Broadcast Console initialized. User ID: {{ auth()->id() }}');
        });
    </script>

</body>
</html>
