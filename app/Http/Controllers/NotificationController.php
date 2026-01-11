<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this notification.');
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.'
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    public function show(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this notification.');
        }

        // Mark as read when viewing
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        // Determine redirect URL based on notification type and notifiable
        $redirectUrl = null;
        
        if ($notification->notifiable_type && $notification->notifiable_id) {
            $notifiable = $notification->notifiable;
            
            if ($notifiable) {
                // Phase notifications
                if ($notification->notifiable_type === \App\Models\Phase::class) {
                    $redirectUrl = route('phases.show', $notifiable);
                }
                // Beneficiary notifications
                elseif ($notification->notifiable_type === \App\Models\Beneficiary::class) {
                    if (str_contains($notification->type, 'approved')) {
                        $redirectUrl = route('admin-hq.show-approved-case', $notifiable);
                    } elseif (str_contains($notification->type, 'rejected')) {
                        $redirectUrl = route('admin-hq.show-rejected-case', $notifiable);
                    } else {
                        $redirectUrl = route('beneficiaries.show', $notifiable);
                    }
                }
            }
        }

        // If no specific redirect, go to notifications index
        if (!$redirectUrl) {
            $redirectUrl = route('notifications.index');
        }

        return redirect($redirectUrl);
    }

    public function destroy(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this notification.');
        }

        $notification->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted.'
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }
}


