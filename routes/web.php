<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AccountSettingsController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware([\App\Http\Middleware\HeadlessMiddleware::class])->group(function () {
    // Default named login route for Laravel's Authenticate middleware
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        // Guest Auth Routes
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

        // Authenticated Admin Routes
        Route::middleware(['auth:web'])->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

            Route::get('/', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            // Profile Routes
            Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
            Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

            // Account & Site Settings Routes
            Route::get('account-settings', [AccountSettingsController::class, 'index'])->name('settings.index');
            Route::put('account-settings/password', [AccountSettingsController::class, 'updatePassword'])->name('settings.password');
            Route::put('account-settings/site', [AccountSettingsController::class, 'updateSite'])->name('settings.site');

            // Activity Logs Route
            Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

            // Realtime Notification Routes
            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
            Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
            Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
            Route::delete('notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

            Route::resource('users', UserController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('products', ProductController::class);
        });
    });
});
