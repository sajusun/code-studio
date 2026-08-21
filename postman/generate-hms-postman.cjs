const fs = require('fs');
const path = require('path');

const outDir = __dirname;

const accept = { key: 'Accept', value: 'application/json' };
const jsonType = { key: 'Content-Type', value: 'application/json' };
const xsrf = { key: 'X-XSRF-TOKEN', value: '{{xsrf_token}}', description: 'Set by Auth / Get CSRF cookie' };

const vars = [
  ['app_url', 'http://localhost:8000'],
  ['base_url', 'http://localhost:8000/api/v1'],
  ['admin_email', 'admin@hms.com'],
  ['admin_password', 'password'],
  ['xsrf_token', ''],
  ['room_id', '1'],
  ['room_type_id', '1'],
  ['guest_id', '1'],
  ['reservation_id', '1'],
  ['stay_id', '1'],
  ['invoice_id', '1'],
  ['conversation_id', '1'],
  ['notification_id', ''],
  ['check_in_date', ''],
  ['check_out_date', ''],
  ['invoice_total', '100.00'],
];

function rawUrl(url, query = []) {
  if (!query.length) return url;
  return {
    raw: `${url}?${query.map((q) => `${q.key}=${q.value}`).join('&')}`,
    host: [url],
    query,
  };
}

function body(obj) {
  return { mode: 'raw', raw: JSON.stringify(obj, null, 2) };
}

function req(name, method, url, opts = {}) {
  const hasJson = opts.body !== undefined;
  const isWrite = !['GET', 'HEAD'].includes(method);
  const headers = [accept];
  if (hasJson) headers.push(jsonType);
  if (opts.csrf || (isWrite && opts.authenticated)) headers.push(xsrf);

  const request = {
    method,
    header: headers,
    url: opts.query ? rawUrl(url, opts.query) : url,
  };

  if (hasJson) request.body = body(opts.body);
  if (opts.description) request.description = opts.description;

  const item = { name, request };
  if (opts.event) item.event = opts.event;
  return item;
}

function setVar(varName, expression = 'json.data.id', status = 200) {
  return [{
    listen: 'test',
    script: {
      type: 'text/javascript',
      exec: [
        `if (pm.response.code === ${status}) {`,
        '  const json = pm.response.json();',
        `  const value = ${expression};`,
        `  if (value !== undefined && value !== null) pm.collectionVariables.set('${varName}', value);`,
        '}',
      ],
    },
  }];
}

function tests(lines) {
  return [{ listen: 'test', script: { type: 'text/javascript', exec: lines } }];
}

const collection = {
  info: {
    _postman_id: 'hms-api-collection-v2',
    name: 'HMS - Hotel Management System API',
    description: [
      'Laravel HMS backend API collection for `/api/v1`.',
      '',
      'Import one environment from this folder, then run `Auth / Get CSRF cookie` and `Auth / Login` before protected endpoints.',
      '',
      'Seeded admin credentials: `admin@hms.com` / `password`.',
      'Collection scripts auto-fill future booking dates and capture IDs from common responses.',
    ].join('\n'),
    schema: 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
  },
  variable: vars.map(([key, value]) => ({ key, value, type: 'string' })),
  event: [{
    listen: 'prerequest',
    script: {
      type: 'text/javascript',
      exec: [
        'const daysAhead = (n) => {',
        '  const d = new Date();',
        '  d.setDate(d.getDate() + n);',
        "  return d.toISOString().split('T')[0];",
        '};',
        "if (!pm.collectionVariables.get('check_in_date')) pm.collectionVariables.set('check_in_date', daysAhead(7));",
        "if (!pm.collectionVariables.get('check_out_date')) pm.collectionVariables.set('check_out_date', daysAhead(10));",
      ],
    },
  }],
  item: [
    {
      name: 'Auth',
      description: 'Cookie/session auth via Laravel Sanctum. Postman will store the cookies after these requests.',
      item: [
        {
          name: 'Get CSRF cookie',
          request: { method: 'GET', header: [accept], url: '{{app_url}}/sanctum/csrf-cookie' },
          event: tests([
            "const cookie = pm.cookies.get('XSRF-TOKEN');",
            'if (cookie) pm.collectionVariables.set(\'xsrf_token\', decodeURIComponent(cookie));',
            "pm.test('CSRF cookie request completed', () => pm.expect(pm.response.code).to.be.oneOf([204, 200]));",
          ]),
        },
        req('Login', 'POST', '{{base_url}}/login', {
          csrf: true,
          body: { email: '{{admin_email}}', password: '{{admin_password}}' },
          event: tests([
            "pm.test('Logged in', () => pm.expect(pm.response.code).to.be.oneOf([200, 204]));",
            'if (pm.response.code === 200) {',
            '  const json = pm.response.json();',
            "  if (json.user && json.user.id) pm.collectionVariables.set('user_id', json.user.id);",
            '}',
          ]),
        }),
        req('Current user', 'GET', '{{base_url}}/user'),
        req('Logout', 'POST', '{{base_url}}/logout', { authenticated: true }),
      ],
    },
    {
      name: 'Public',
      item: [
        req('List public room types', 'GET', '{{base_url}}/public/room-types'),
        req('Search public availability', 'GET', '{{base_url}}/public/availability', {
          query: [
            { key: 'check_in_date', value: '{{check_in_date}}' },
            { key: 'check_out_date', value: '{{check_out_date}}' },
            { key: 'guests_count', value: '2' },
          ],
          event: setVar('room_id', 'json.data && json.data[0] && json.data[0].id'),
        }),
        req('Create public reservation', 'POST', '{{base_url}}/public/reservations', {
          body: {
            first_name: 'Public',
            last_name: 'Guest',
            email: 'public.guest.{{$timestamp}}@example.com',
            phone: '+1-555-0144',
            room_id: '{{room_id}}',
            check_in_date: '{{check_in_date}}',
            check_out_date: '{{check_out_date}}',
            guests_count: 2,
            special_requests: 'Near elevator if available',
          },
          event: setVar('reservation_id', 'json.data && json.data.id', 201),
        }),
        req('Create public support contact', 'POST', '{{base_url}}/public/support/contact', {
          body: {
            customer_email: 'guest.{{$timestamp}}@example.com',
            customer_name: 'Website Guest',
            subject: 'Booking question',
            message: 'Do you offer airport pickup?',
          },
          event: setVar('conversation_id', 'json.data && json.data.id'),
        }),
        req('Subscribe newsletter', 'POST', '{{base_url}}/newsletter/subscribe', {
          body: { email: 'subscriber.{{$timestamp}}@example.com' },
        }),
      ],
    },
    {
      name: 'Workflow',
      description: 'Run after Auth / Get CSRF cookie and Auth / Login.',
      item: [
        req('1. Search availability', 'GET', '{{base_url}}/availability', {
          query: [
            { key: 'check_in_date', value: '{{check_in_date}}' },
            { key: 'check_out_date', value: '{{check_out_date}}' },
            { key: 'guests_count', value: '2' },
          ],
          event: setVar('room_id', 'json.data && json.data[0] && json.data[0].id'),
        }),
        req('2. Create guest', 'POST', '{{base_url}}/guests', {
          authenticated: true,
          body: {
            first_name: 'Alice',
            last_name: 'Traveler',
            email: 'alice.traveler.{{$timestamp}}@example.com',
            phone: '+1-555-0199',
            document_number: 'P99887766',
            notes: 'Created from Postman workflow',
          },
          event: setVar('guest_id', 'json.data && json.data.id', 201),
        }),
        req('3. Create reservation', 'POST', '{{base_url}}/reservations', {
          authenticated: true,
          body: {
            room_id: '{{room_id}}',
            guest_id: '{{guest_id}}',
            check_in_date: '{{check_in_date}}',
            check_out_date: '{{check_out_date}}',
            guests_count: 2,
            special_requests: 'Late check-in requested',
          },
          event: setVar('reservation_id', 'json.data && json.data.id', 201),
        }),
        req('4. Check in', 'POST', '{{base_url}}/reservations/{{reservation_id}}/check-in', {
          authenticated: true,
          event: tests([
            "pm.test('Checked in', () => pm.expect(pm.response.code).to.be.oneOf([200, 201]));",
            'const json = pm.response.json();',
            "if (json.data && json.data.id) pm.collectionVariables.set('stay_id', json.data.id);",
            "if (json.data && json.data.invoice && json.data.invoice.id) pm.collectionVariables.set('invoice_id', json.data.invoice.id);",
          ]),
        }),
        req('5. Add service charge', 'POST', '{{base_url}}/invoices/{{invoice_id}}/services', {
          authenticated: true,
          body: { description: 'Room service breakfast', unit_price: 25, quantity: 2, type: 'service' },
        }),
        req('6. Get invoice', 'GET', '{{base_url}}/invoices/{{invoice_id}}', {
          event: tests([
            "pm.test('Status 200', () => pm.response.to.have.status(200));",
            'const json = pm.response.json();',
            "if (json.data && json.data.total_amount) pm.collectionVariables.set('invoice_total', json.data.total_amount);",
          ]),
        }),
        req('7. Check out', 'POST', '{{base_url}}/stays/{{stay_id}}/check-out', { authenticated: true }),
        req('8. Record payment', 'POST', '{{base_url}}/invoices/{{invoice_id}}/payments', {
          authenticated: true,
          body: { amount: '{{invoice_total}}', method: 'card', transaction_reference: 'TXN-{{$timestamp}}' },
        }),
      ],
    },
    {
      name: 'Rooms',
      item: [
        req('List room types', 'GET', '{{base_url}}/room-types'),
        req('List rooms', 'GET', '{{base_url}}/rooms'),
        req('List rooms by status', 'GET', '{{base_url}}/rooms', {
          query: [{ key: 'status', value: 'available', description: 'available | reserved | occupied | maintenance' }],
        }),
        req('Update room status', 'PATCH', '{{base_url}}/rooms/{{room_id}}/status', {
          authenticated: true,
          body: { status: 'maintenance' },
          description: 'Status values: available, reserved, occupied, maintenance',
        }),
      ],
    },
    {
      name: 'Guests',
      item: [
        req('List guests', 'GET', '{{base_url}}/guests'),
        req('Create guest', 'POST', '{{base_url}}/guests', {
          authenticated: true,
          body: {
            first_name: 'Jane',
            last_name: 'Doe',
            email: 'jane.doe.{{$timestamp}}@example.com',
            phone: '+1-555-0100',
            document_number: 'P12345678',
            notes: 'VIP guest',
          },
          event: setVar('guest_id', 'json.data && json.data.id', 201),
        }),
        req('Get guest', 'GET', '{{base_url}}/guests/{{guest_id}}'),
        req('Update guest', 'PUT', '{{base_url}}/guests/{{guest_id}}', {
          authenticated: true,
          body: { phone: '+1-555-9999', notes: 'Updated contact number' },
        }),
      ],
    },
    {
      name: 'Reservations',
      item: [
        req('Search availability', 'GET', '{{base_url}}/availability', {
          query: [
            { key: 'check_in_date', value: '{{check_in_date}}' },
            { key: 'check_out_date', value: '{{check_out_date}}' },
            { key: 'room_type_id', value: '{{room_type_id}}', disabled: true },
            { key: 'guests_count', value: '2' },
          ],
          event: setVar('room_id', 'json.data && json.data[0] && json.data[0].id'),
        }),
        req('List reservations', 'GET', '{{base_url}}/reservations'),
        req('Create reservation', 'POST', '{{base_url}}/reservations', {
          authenticated: true,
          body: {
            room_id: '{{room_id}}',
            guest_id: '{{guest_id}}',
            check_in_date: '{{check_in_date}}',
            check_out_date: '{{check_out_date}}',
            guests_count: 2,
            special_requests: 'High floor preferred',
          },
          event: setVar('reservation_id', 'json.data && json.data.id', 201),
        }),
        req('Get reservation', 'GET', '{{base_url}}/reservations/{{reservation_id}}'),
        req('Cancel reservation', 'POST', '{{base_url}}/reservations/{{reservation_id}}/cancel', { authenticated: true }),
      ],
    },
    {
      name: 'Stays',
      item: [
        req('List stays', 'GET', '{{base_url}}/stays'),
        req('Get stay', 'GET', '{{base_url}}/stays/{{stay_id}}'),
        req('Check in from reservation', 'POST', '{{base_url}}/reservations/{{reservation_id}}/check-in', {
          authenticated: true,
          description: 'Creates a stay and draft invoice. Room status becomes occupied.',
        }),
        req('Check out', 'POST', '{{base_url}}/stays/{{stay_id}}/check-out', {
          authenticated: true,
          description: 'Completes stay, issues invoice if draft, and frees the room.',
        }),
      ],
    },
    {
      name: 'Billing',
      item: [
        req('List invoices', 'GET', '{{base_url}}/invoices'),
        req('Get invoice', 'GET', '{{base_url}}/invoices/{{invoice_id}}'),
        req('Add service charge', 'POST', '{{base_url}}/invoices/{{invoice_id}}/services', {
          authenticated: true,
          body: { description: 'Spa treatment', unit_price: 75, quantity: 1, type: 'service' },
        }),
        req('Issue invoice', 'POST', '{{base_url}}/invoices/{{invoice_id}}/issue', {
          authenticated: true,
          description: 'Manually issue a draft invoice. Checkout also issues automatically.',
        }),
        req('Record payment', 'POST', '{{base_url}}/invoices/{{invoice_id}}/payments', {
          authenticated: true,
          body: { amount: 100, method: 'card', transaction_reference: 'TXN-12345' },
          description: 'Payment methods: cash, card, bank_transfer, other',
        }),
      ],
    },
    {
      name: 'Settings',
      item: [
        req('Get site settings', 'GET', '{{base_url}}/settings/site'),
        req('Update site settings', 'PUT', '{{base_url}}/settings/site', {
          authenticated: true,
          body: {
            name: 'HMS',
            title: 'HMS Admin',
            description: 'Hotel management and guest booking platform',
            tagline: 'Comfort, handled cleanly',
            seo_keywords: 'hotel,booking,reservations',
            logo_url: '',
            favicon_url: '',
            owner_name: 'Hotel Owner',
            owner_email: 'owner@example.com',
            owner_phone: '+1-555-0102',
            support_email: 'support@example.com',
            support_phone: '+1-555-0103',
            support_hours: '9am-5pm',
            support_url: 'https://example.com/support',
            newsletter_enabled: true,
            newsletter_from_name: 'HMS',
            newsletter_from_email: 'newsletter@example.com',
            newsletter_reply_to_email: 'support@example.com',
            newsletter_footer_text: 'You are receiving this because you subscribed.',
            newsletter_manage_url: 'https://example.com/newsletter/manage',
          },
        }),
      ],
    },
    {
      name: 'Newsletter',
      item: [
        req('Subscribe', 'POST', '{{base_url}}/newsletter/subscribe', {
          body: { email: 'subscriber.{{$timestamp}}@example.com' },
        }),
        req('List subscribers', 'GET', '{{base_url}}/newsletter/subscribers', {
          query: [
            { key: 'q', value: 'subscriber', disabled: true },
            { key: 'per_page', value: '10' },
          ],
        }),
      ],
    },
    {
      name: 'Support',
      item: [
        req('List conversations', 'GET', '{{base_url}}/support/conversations', {
          query: [
            { key: 'status', value: 'open', disabled: true },
            { key: 'q', value: 'booking', disabled: true },
            { key: 'per_page', value: '10' },
          ],
        }),
        req('Create conversation', 'POST', '{{base_url}}/support/conversations', {
          authenticated: true,
          body: {
            customer_email: 'guest.{{$timestamp}}@example.com',
            customer_name: 'Desk Guest',
            subject: 'Invoice question',
            message: 'Could you resend my invoice?',
          },
          event: setVar('conversation_id', 'json.data && json.data.id'),
        }),
        req('Get conversation', 'GET', '{{base_url}}/support/conversations/{{conversation_id}}'),
        req('Update conversation status', 'PATCH', '{{base_url}}/support/conversations/{{conversation_id}}/status', {
          authenticated: true,
          body: { status: 'pending' },
          description: 'Status values: open, pending, closed',
        }),
        req('Reply to conversation', 'POST', '{{base_url}}/support/conversations/{{conversation_id}}/reply', {
          authenticated: true,
          body: {
            message: 'Thanks for reaching out. We are checking this now.',
            subject: 'Re: Invoice question',
          },
        }),
      ],
    },
    {
      name: 'Notifications',
      item: [
        req('List notifications', 'GET', '{{base_url}}/notifications', {
          event: tests([
            "pm.test('Status 200', () => pm.response.to.have.status(200));",
            'const json = pm.response.json();',
            "if (json.data && json.data[0] && json.data[0].id) pm.collectionVariables.set('notification_id', json.data[0].id);",
          ]),
        }),
        req('Mark notification as read', 'POST', '{{base_url}}/notifications/{{notification_id}}/read', { authenticated: true }),
        req('Mark all notifications as read', 'POST', '{{base_url}}/notifications/read-all', { authenticated: true }),
      ],
    },
  ],
};

function environment(id, name, appUrl) {
  const apiUrl = `${appUrl}/api/v1`;
  return {
    id,
    name,
    values: vars.map(([key, defaultValue]) => ({
      key,
      value: key === 'app_url' ? appUrl : key === 'base_url' ? apiUrl : defaultValue,
      type: 'default',
      enabled: true,
    })),
    _postman_variable_scope: 'environment',
    _postman_exported_at: new Date().toISOString(),
    _postman_exported_using: 'HMS Postman Generator',
  };
}

fs.writeFileSync(
  path.join(outDir, 'HMS-API.postman_collection.json'),
  `${JSON.stringify(collection, null, 2)}\n`,
);
fs.writeFileSync(
  path.join(outDir, 'HMS-Local.postman_environment.json'),
  `${JSON.stringify(environment('hms-local-environment', 'HMS - Local (artisan serve)', 'http://localhost:8000'), null, 2)}\n`,
);
fs.writeFileSync(
  path.join(outDir, 'HMS-Herd.postman_environment.json'),
  `${JSON.stringify(environment('hms-herd-environment', 'HMS - Laravel Herd', 'http://hms.test'), null, 2)}\n`,
);

console.log('Generated Postman collection and environments in backend/postman');
