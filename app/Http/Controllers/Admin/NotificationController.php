<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NotificationController extends Controller
{
    public function markAllAsRead(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('notifications')) {
            return back()->with('error', 'Notifications are not available until the database migration is run.');
        }

        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Notifications marked as read.');
    }
}
