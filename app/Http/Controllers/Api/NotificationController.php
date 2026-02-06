<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helper\ApiResponse;


class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(10);
        $notifications_count = $user->notifications()->count();

        $data = [
            'notifications_count' => $notifications_count,
            'notifications' => NotificationResource::collection($notifications)
        ];
        return ApiResponse::sendResponse(
            200,
            'null',
            $data
        );
    }


    public function isRead(string $id)
    {

        $user = auth()->user();
        $notification = Notification::find($id);

        if (!$notification) {
            return ApiResponse::sendResponse(
                404,
                'Notification not found',
                null
            );
        }
        $user_notification = $user->notifications()->where('id', $notification->id)->first();


        if (!$user_notification) {
            return ApiResponse::sendResponse(
                403,
                'This notification does not belong to you',
                null
            );
        }

        $is_read = $user_notification->is_read;
        if ($is_read == 0) {
            $user_notification->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

            $data = [
                'read_at' => now()
            ];

            return ApiResponse::sendResponse(
                200,
                'Notification marked as read',
                $data
            );
        } else {

            return ApiResponse::sendResponse(
                200,
                'Notification already marked as read',
                null
            );
        }
    }
}
