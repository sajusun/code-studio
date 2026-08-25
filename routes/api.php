<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\V1\AuditController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PublicDeveloperController;
use App\Http\Controllers\Api\Admin\DeveloperController;
use App\Http\Controllers\Api\Admin\DeveloperRoleController;
use App\Modules\Auth\Http\Controllers\AuthController;
use App\Modules\Room\Http\Controllers\RoomController;
use App\Modules\Stay\Http\Controllers\StayController;
// PermissionController and UserRoleController are handled by UserManagementController
use App\Modules\Guest\Http\Controllers\GuestController;
use App\Http\Controllers\Api\V1\UserManagementController;
use App\Modules\Room\Http\Controllers\RoomTypeController;
use App\Modules\Billing\Http\Controllers\InvoiceController;
use App\Modules\Billing\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\NewsletterSubscriberController;
use App\Modules\Reservation\Http\Controllers\ReservationController;

Route::prefix('v1')->group(function () {
    // Core Modules API Endpoints
    Route::prefix('verification')->group(function () {
        Route::post('send', [\App\Http\Controllers\Api\VerificationController::class, 'send']);
        Route::post('verify-otp', [\App\Http\Controllers\Api\VerificationController::class, 'verifyOtp']);
        Route::get('verify-token', [\App\Http\Controllers\Api\VerificationController::class, 'verifyToken']);
        Route::post('resend', [\App\Http\Controllers\Api\VerificationController::class, 'resend']);
    });

    Route::prefix('media')->group(function () {
        Route::post('upload', [\App\Modules\Media\Controllers\MediaController::class, 'store']);
        Route::delete('{id}', [\App\Modules\Media\Controllers\MediaController::class, 'destroy']);
    });

    Route::prefix('payment')->group(function () {
        Route::post('checkout', [\App\Modules\Payment\Controllers\PaymentController::class, 'checkout']);
        Route::get('verify/{transactionId}', [\App\Modules\Payment\Controllers\PaymentController::class, 'verify']);
        Route::post('webhook/{provider}', [\App\Modules\Payment\Controllers\PaymentController::class, 'webhook']);
    });

    // Public Webhooks
    Route::post('webhooks/stripe', [PaymentController::class, 'stripeWebhook']);
    Route::post('webhooks/paypal', [PaymentController::class, 'paypalWebhook']);

    // Public Newsletter Subscribe
    Route::post('newsletter/subscribe', [NewsletterSubscriberController::class, 'store']);

    // Public showcase & guest routes
    Route::prefix('public')->group(function () {
        Route::get('products', [\App\Http\Controllers\Api\PublicProductController::class, 'index']);
        Route::get('products/featured', [\App\Http\Controllers\Api\PublicProductController::class, 'featured']);
        Route::get('products/{slug}', [\App\Http\Controllers\Api\PublicProductController::class, 'show']);
        Route::get('room-types', [RoomController::class, 'roomTypes']);
        Route::get('availability', [ReservationController::class, 'searchAvailability']);
        Route::post('reservations', [ReservationController::class, 'storePublic']);
        Route::post('support/contact', [SupportController::class, 'createConversation']);
        // Public developer showcase
        Route::get('developers', [PublicDeveloperController::class, 'index']);
        Route::get('developers/{developer}', [PublicDeveloperController::class, 'show']);
        Route::get('developer-roles', [PublicDeveloperController::class, 'roles']);
    });

    // ── Admin Management Routes ───────────────────────────────────────────────
    Route::prefix('admin')
        ->middleware(['auth:sanctum', 'role:admin'])
        ->group(function () {
            // Developer Role Types (specializations)
            Route::apiResource('developer-roles', DeveloperRoleController::class);

            // Developer Profiles
            Route::get('developers', [DeveloperController::class, 'index']);
            Route::post('developers', [DeveloperController::class, 'store']);
            Route::get('developers/{developer}', [DeveloperController::class, 'show']);
            Route::put('developers/{developer}', [DeveloperController::class, 'update']);
            Route::delete('developers/{developer}', [DeveloperController::class, 'destroy']);
            Route::patch('developers/{developer}/status', [DeveloperController::class, 'updateStatus']);
        });

    // Auth Routes
    Route::post('api-login', [AuthController::class, 'apiLogin']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


    // Settings (admin-only for now)
    Route::get('settings/site', [SettingsController::class, 'showSite'])->middleware(['auth:sanctum', 'role:admin']);
    Route::put('settings/site', [SettingsController::class, 'updateSite'])->middleware(['auth:sanctum', 'role:admin']);

    // Newsletter subscribers (admin + help desk)
    Route::get('newsletter/subscribers', [NewsletterSubscriberController::class, 'index'])->middleware(['auth:sanctum', 'role:admin,help_desk']);

    // Role & Permission management (admin only)
    Route::get('roles', [UserManagementController::class, 'getRoles'])->middleware(['auth:sanctum', 'role:admin']);
    Route::get('permissions', [UserManagementController::class, 'getPermissions'])->middleware(['auth:sanctum', 'role:admin']);
    Route::get('users', [UserManagementController::class, 'index'])->middleware(['auth:sanctum', 'role:admin']);
    Route::put('users/{user}/permissions', [UserManagementController::class, 'updatePermissions'])->middleware(['auth:sanctum', 'role:admin']);
    // Sync roles for a user (admin only)
    Route::put('users/{user}/roles', [UserManagementController::class, 'updateRoles'])->middleware(['auth:sanctum', 'role:admin']);
    // Full user update (name, email, roles)
    Route::put('users/{user}', [UserManagementController::class, 'updateUser'])->middleware(['auth:sanctum', 'role:admin']);
    // Delete user (admin only)
    Route::delete('users/{user}', [UserManagementController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);
    // Create a new user with roles (admin only)
    Route::post('users', [UserManagementController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
    Route::get('support/conversations', [SupportController::class, 'index'])->middleware(['auth:sanctum', 'role:admin,help_desk']);
    Route::post('support/conversations', [SupportController::class, 'createConversation'])->middleware(['auth:sanctum', 'role:admin,help_desk']);
    Route::get('support/conversations/{conversation}', [SupportController::class, 'show'])->middleware(['auth:sanctum', 'role:admin,help_desk']);
    Route::patch('support/conversations/{conversation}/status', [SupportController::class, 'updateStatus'])->middleware(['auth:sanctum', 'role:admin,help_desk']);
    Route::post('support/conversations/{conversation}/reply', [SupportController::class, 'reply'])->middleware(['auth:sanctum', 'role:admin,help_desk']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('notifications', [NotificationsController::class, 'index']);
        Route::post('notifications/{id}/read', [NotificationsController::class, 'markAsRead']);
        Route::post('notifications/read-all', [NotificationsController::class, 'markAllAsRead']);

        // Dashboard
        Route::get('dashboard/stats', [DashboardController::class, 'stats']);

        // Rooms
        Route::get('rooms', [RoomController::class, 'index']);
        Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus']);
        Route::middleware('role:admin,manager')->group(function () {
            Route::post('rooms', [RoomController::class, 'store']);
            Route::get('rooms/{room}', [RoomController::class, 'show']);
            Route::put('rooms/{room}', [RoomController::class, 'update']);
            Route::delete('rooms/{room}', [RoomController::class, 'destroy']);
        });

        // Room Types
        Route::get('room-types', [RoomTypeController::class, 'index']);
        Route::middleware('role:admin,manager')->group(function () {
            Route::post('room-types', [RoomTypeController::class, 'store']);
            Route::get('room-types/{room_type}', [RoomTypeController::class, 'show']);
            Route::put('room-types/{room_type}', [RoomTypeController::class, 'update']);
            Route::delete('room-types/{room_type}', [RoomTypeController::class, 'destroy']);
        });

        Route::apiResource('guests', GuestController::class);

        Route::get('reservations', [ReservationController::class, 'index']);
        Route::get('reservations/{reservation}', [ReservationController::class, 'show']);
        Route::post('reservations', [ReservationController::class, 'store']);
        Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
        Route::get('availability', [ReservationController::class, 'searchAvailability']);

        Route::get('stays', [StayController::class, 'index']);
        Route::get('stays/{stay}', [StayController::class, 'show']);
        Route::post('reservations/{reservation}/check-in', [StayController::class, 'checkIn']);
        Route::post('stays/{stay}/check-out', [StayController::class, 'checkOut']);

        Route::get('invoices', [InvoiceController::class, 'index']);
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show']);
        Route::post('invoices/{invoice}/services', [InvoiceController::class, 'addServiceCharge']);
        Route::post('invoices/{invoice}/issue', [InvoiceController::class, 'issue']);
        Route::post('invoices/{invoice}/payments', [InvoiceController::class, 'recordPayment']);
        Route::post('payments/{invoice}/initiate', [PaymentController::class, 'initiate']);
        Route::get('audit-logs', [AuditController::class, 'index'])->middleware(['auth:sanctum', 'role:admin']);
    });
});
