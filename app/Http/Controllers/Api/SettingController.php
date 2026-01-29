<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helper\ApiResponse;
use Illuminate\Support\Facades\Auth;


class SettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();


        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {

            return ApiResponse::sendResponse(
                422,
                'Password is incorrect',
                null
            );
        }

        $user->tokens()->delete();
        $user->delete();

        return ApiResponse::sendResponse(
            200,
            'Deleted Successfully',
            null
        );
    }

    public function languages()
    {
        $languages = Language::all();
        return ApiResponse::sendResponse(
            200,
            'null',
            $languages
        );
    }
}
