<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helper\ApiResponse;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\HelpItemResource;
use App\Http\Resources\PrivacyResource;
use App\Http\Resources\AboutAppResource;
use App\Models\AppSetting;
use App\Models\HelpItem;
use App\Models\PrivacySetting;

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

        $user = auth()->user();

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

    public function helpItem()
    {
        $help_item = HelpItem::find(1);

        $data = [
            'help_item' => new HelpItemResource($help_item)
        ];

        return ApiResponse::sendResponse(
            200,
            'null',
            $data
        );
    }

    public function privacySetting(Request $request)
    {
        $user_id = auth()->id();

        $privacy_setting = PrivacySetting::where('user_id', $user_id)->first();

        $data = [
            'privacy_setting' => new PrivacyResource($privacy_setting)
        ];

        return ApiResponse::sendResponse(
            200,
            'null',
            $data
        );
    }

    public function appSetting()
    {
        $about_app = AppSetting::find(1);

        $data = [
            'about_app' => new AboutAppResource($about_app)
        ];
        return ApiResponse::sendResponse(
            200,
            'null',
            $data
        );
    }
}
