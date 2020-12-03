<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $user->unreadNotifications->markAsRead();

        $notifications = $user->notifications();

        if ($request->has('sort')) {
            list($sortCol, $sortDesc) = explode('|', request()->sort);
            
            ($sortDesc == 'true') ? $sortDir = 'desc' : $sortDir = 'asc';

            $notifications = $notifications->orderBy($sortCol, $sortDir);
        }

        $perPage = $request->has('size') ? (int) $request->size : null;

        return response()->json($notifications->paginate($perPage), 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->whereId($id)->firstOrFail();

        $notification->markAsRead();
        
        return response()->json($notification, 200);
    }

    public function getLatest($take)
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications()->take($take)->get();
        return response()->json($notifications, 200);
    }

    public function getAllUnread()
    {
        $user = Auth::user();

        $notifications = $user->unreadNotifications;
        return response()->json($notifications, 200);
    }

    public function markAllRead()
    {
        $user = Auth::user();

        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications()->get();
        return response()->json($notifications, 200);
    }
}
