<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorWorking;
use App\Models\DoctorWorkingHoursOnline;
use App\Models\Review;
use Illuminate\Http\Request;

class DoctorFilteringController extends Controller
{
    public function search(Request $request)
    {
        $doctors = Doctor::with(['user' , 'spelization' , 'clinic' , 'workingHours'])

        ->where('is_verified', true)
        ->when($request->search, function ($query) use ($request) {

            $query->where(function ($q) use ($request) {

                $q->whereHas('user', function ($srch) use ($request) {
                    $srch->where('name', 'like', "%{$request->search}%");
                })

                ->orWhereHas('spelization', function ($filter) use ($request) {
                    $filter->where('name', 'like', "%{$request->search}%");
                })

                ->orWhereHas('clinic', function ($cli) use ($request) {
                    $cli->where('name', 'like', "%{$request->search}%")
                        ->orWhere('address', 'like', "%{$request->search}%");
                })

                ->orWhereHas('workingHours', function ($work) use ($request) {
                    $work->where('is_closed', $request->search == 'closed' ? true : false);
                });
            });
        })
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'No doctors found',
            'data' => $doctors,
        ]);
    }

    public function doctorsInformation($id)
    {
        $doctor = Doctor::with([
            'user:id,name,email,phone',
            'clinic:id,name,address',
            'spelization:id,name'
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Doctors Informations',
            'data' => $doctor
        ]);

    }

    public function patientReviews($id)
    {
        $reviews = Review::with(
            'patient.user:id,name',
        )
        ->where('doctor_id' , $id)
        ->paginate();

        return response()->json([
            'status' => true,
            'message' => 'Patients Reviews',
            'data' => $reviews,
        ] , 200);
    }

    public function workingHours($id)
    {
        $hours = DoctorWorking::with('doctor.user:id,name')

        ->where('doctor_id', $id)
        ->orderByRaw("FIELD(day_of_week,
            'Monday','Tuesday','Wednesday',
            'Thursday','Friday','Saturday','Sunday')")
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Doctors Working Hours',
            'data' => $hours
        ]);
    }


    public function workingHoursOnline($id)
    {
        $onlineHours = DoctorWorkingHoursOnline::with('doctor.user:id,name')

        ->where('doctor_id', $id)
        ->orderByRaw("FIELD(day_of_week,
            'Monday','Tuesday','Wednesday',
            'Thursday','Friday','Saturday','Sunday')")
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Doctors Working Hours Online',
            'data' => $onlineHours,
        ]);
    }
}