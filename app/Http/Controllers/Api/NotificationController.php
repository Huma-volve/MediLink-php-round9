<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helper\ApiResponse;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->get();
        $data = [
            'notifications' => NotificationResource::collection($notifications)
        ];
           return ApiResponse::sendResponse(
                200,
                'null',
                $data
            );
    }


    public function isRead(Request $request, string $id)
    {

        $user = $request->user();
        $notification = Notification::find($id);

        if (!$notification) {
               return ApiResponse::sendResponse(
                404,
                'Notification not found',
                null
            );
        }
        $notification = $user->notifications()->where('id', $notification->id)->first();

        if ($notification) {
            $notification->update(['is_read' => 1]);
                 return ApiResponse::sendResponse(
                200,
                'Notification marked as read',
                null
            );
        } else {
                 return ApiResponse::sendResponse(
                403,
                'This notification does not belong to you',
                null
            );
        }
    }
}
