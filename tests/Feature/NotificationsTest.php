<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\NewsletterSubscriber;
use App\Models\SupportConversation;
use App\Modules\Guest\Models\Guest;
use App\Modules\Reservation\Models\Reservation;
use App\Modules\Room\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Shared\Enums\RoomStatus;
use App\Notifications\NewNewsletterSubscriber;
use App\Notifications\NewReservation;
use App\Notifications\NewSupportTicket;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->user);
    }

    public function test_can_fetch_notifications(): void
    {
        $this->user->notify(new NewNewsletterSubscriber(
            new NewsletterSubscriber(['id' => 1, 'email' => 'subscriber@example.com'])
        ));

        $response = $this->getJson('/api/v1/notifications');

        $response->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'target_id',
                        'title',
                        'message',
                        'read_at',
                        'created_at',
                    ]
                ],
                'unread_count'
            ]);
    }

    public function test_can_mark_notification_as_read(): void
    {
        $this->user->notify(new NewNewsletterSubscriber(
            new NewsletterSubscriber(['id' => 1, 'email' => 'subscriber@example.com'])
        ));

        $notificationId = $this->user->unreadNotifications()->first()->id;

        $response = $this->postJson("/api/v1/notifications/{$notificationId}/read");

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    public function test_can_mark_all_notifications_as_read(): void
    {
        $this->user->notify(new NewNewsletterSubscriber(
            new NewsletterSubscriber(['id' => 1, 'email' => 'subscriber@example.com'])
        ));
        $this->user->notify(new NewNewsletterSubscriber(
            new NewsletterSubscriber(['id' => 2, 'email' => 'subscriber2@example.com'])
        ));

        $this->assertEquals(2, $this->user->unreadNotifications()->count());

        $response = $this->postJson('/api/v1/notifications/read-all');

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertEquals(0, $this->user->unreadNotifications()->count());
    }

    public function test_newsletter_subscriber_triggers_notification(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/newsletter/subscribe', [
            'email' => 'newsubscriber@example.com',
        ]);

        $response->assertOk();

        Notification::assertSentTo(
            [$this->user],
            NewNewsletterSubscriber::class
        );
    }

    public function test_public_support_contact_triggers_notification(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/public/support/contact', [
            'customer_email' => 'customer@example.com',
            'customer_name' => 'Jane Doe',
            'subject' => 'Help needed',
            'message' => 'I cannot book a room.',
        ]);

        $response->assertOk();

        Notification::assertSentTo(
            [$this->user],
            NewSupportTicket::class
        );
    }

    public function test_reservation_creation_triggers_notification(): void
    {
        Notification::fake();

        $type = RoomType::query()->create([
            'name' => 'Standard',
            'base_rate' => 100.00,
            'capacity' => 2,
        ]);

        $room = Room::query()->create([
            'room_type_id' => $type->id,
            'number' => '102',
            'floor' => 1,
            'status' => RoomStatus::Available,
        ]);

        $guest = Guest::query()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@example.com',
        ]);

        $checkIn = Carbon::today()->addDays(5)->toDateString();
        $checkOut = Carbon::today()->addDays(8)->toDateString();

        $response = $this->postJson('/api/v1/reservations', [
            'room_id' => $room->id,
            'guest_id' => $guest->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'guests_count' => 2,
        ]);

        $response->assertCreated();

        Notification::assertSentTo(
            [$this->user],
            NewReservation::class
        );
    }

    public function test_support_reply_email_rendering(): void
    {
        $conv = SupportConversation::query()->create([
            'subject' => 'Billing Question',
            'status' => 'open',
            'customer_email' => 'customer@example.com',
            'customer_name' => 'Jane Guest',
            'last_message_at' => now(),
        ]);

        $msg1 = \App\Models\SupportMessage::query()->create([
            'conversation_id' => $conv->id,
            'direction' => 'in',
            'from_email' => 'customer@example.com',
            'body' => 'How do I pay my invoice?',
            'received_at' => now(),
        ]);

        $msg2 = \App\Models\SupportMessage::query()->create([
            'conversation_id' => $conv->id,
            'direction' => 'out',
            'from_email' => null,
            'to_email' => 'customer@example.com',
            'subject' => 'Re: Billing Question',
            'body' => 'You can pay using credit card online.',
            'sent_at' => now(),
        ]);

        $mailable = new \App\Mail\SupportReplyMail($conv, $msg2);

        $mailable->assertSeeInHtml('Billing Question');
        $mailable->assertSeeInHtml('Jane Guest');
        $mailable->assertSeeInHtml('You can pay using credit card online.');
        $mailable->assertSeeInHtml('How do I pay my invoice?');
    }
}
