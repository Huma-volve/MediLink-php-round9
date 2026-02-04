<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorSearchController extends Controller
{

    public function search(Request $request)
    {

        $doctors = Doctor::with(['user:id,name', 'specialization:id,name'])
            ->where('is_verified', true)
            ->when($request->name, function ($query) use ($request) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('users.name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->speciality_id, function ($query) use ($request) {

                $query->where('specialization_id', $request->speciality_id);
            })
            ->when($request->city, function ($query) use ($request) {

                $query->where('location', 'like', '%' . $request->city . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ], 200);
    }
}
