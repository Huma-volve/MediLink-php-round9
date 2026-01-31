<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helper\ApiResponse;
use App\Http\Resources\LanguageResource;
use App\Models\Setting;

class SettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

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
        $data = [
            'languages' => LanguageResource::collection($languages)
        ];
        return ApiResponse::sendResponse(
            200,
            'null',
            $data
        );
    }

    public function help()
    {
        $setting = Setting::find(1);
        $help = $setting->help;
        return ApiResponse::sendResponse(
            200,
            'null',
            $help
        );
    }

    public function privacy()
    {
        $setting = Setting::find(1);
        $privacy = $setting->privacy;
        return ApiResponse::sendResponse(
            200,
            'null',
            $privacy
        );
    }
    
    public function about()
    {
        $setting = Setting::find(1);
        $about = $setting->about;
        return ApiResponse::sendResponse(
            200,
            'null',
            $about
        );
    }
}
