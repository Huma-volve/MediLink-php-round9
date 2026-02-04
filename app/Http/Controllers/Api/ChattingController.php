<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class ChattingController extends Controller
{
    public function createMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return ApiResponse::sendResponse(200 , 'Message Sent Successfully' , $message);
    }

    public function showMessage(Request $request , $userId)
    {
        $currentUser = $request->user();

        $messages = Message::where(function($query) use ($currentUser , $userId) {
            $query->where('sender_id' , $currentUser->id)
            ->where('receiver_id' , $userId);
        })

        ->orWhere(function ($query) use ($currentUser , $userId) {
            $query->where('sender_id' , $userId)
            ->where('receiver_id' , $currentUser->id);
        })
        ->orderBy('created_at')
        ->get();
        
        if (!$messages) {
            return ApiResponse::error(404 , 'Message Not Found');
        } else {
            return ApiResponse::sendResponse(200 , 'Showing Messages' , $messages);
        }
    }

    public function markAsRead(Request $request , $userId)
    {
        $currentUser = $request->user();

        $read = Message::where('sender_id' , $userId)
        ->where('receiver_id' , $currentUser->id)
        ->where('is_read' , false)
        ->update(['is_read' => true]);

        return ApiResponse::sendResponse(200 , 'Message Was Read Successfully' , $read);
    }

    public function countMessage(Request $request , $userId)
    {
        $user = $request->user();

        $countUnread = Message::where('sender_id' , $userId)
        ->where('receiver_id' , $user->id)
        ->where('is_read' , false)
        ->count();

        return ApiResponse::sendResponse(200 , 'count unread messages id ' , $countUnread);
    }
}
