<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingPatient extends Controller
{
    // Get profile information

    public function index(Request $request)
    {
        $user = $request->user()->load('patient');

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // Update Profile Information
    public function updateSettings(Request $request)
    {
        $user = $request->user();

        // Validation
        $request->validate([
            // Users table
            'name'   => 'sometimes|string|max:255',
            'phone'  => 'sometimes|string|unique:users,phone,' . $user->id,
            'gender' => 'sometimes|in:male,female',
            'profile_picture' => 'nullable|file',

            // Patients table
            'blood_group' => 'sometimes|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'date_of_birth' => 'sometimes|date',
            'weight' => 'sometimes|numeric',
            'height' => 'sometimes|numeric',
            'emergency_contact_name' => 'sometimes|string',
            'emergency_contact_phone' => 'sometimes|string',
            'emergency_contact_relationship' => 'sometimes|string',
        ]);

        // Update user data

        $user->update($request->only([
            'name',
            'phone',
            'gender'
        ]));

        // Update or create patient data

        $user->patient()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'blood_group',
                'date_of_birth',
                'weight',
                'height',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship'
            ])
        );

        // Upload profile picture

        if ($request->hasFile('profile_picture')) {

            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $user->profile_picture = $request
                ->file('profile_picture')
                ->store('profiles', 'public');

            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    // Change password

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'كلمة المرور الحالية غير صحيحة'
            ], 400);
        }

        $request->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ]);
    }

    // Delete account
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحساب بنجاح'
        ]);
    }

    //Payment history

    public function paymentHistory()
    {
        $user = auth()->user();

        if (!$user->patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient profile not found'
            ], 404);
        }

        $history = Payment::where('patient_id', $user->patient->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }
}
