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
        $doctors = Doctor::with([
            'user:id,name,email',
            'spelization:id,name',
            'clinic:id,name,address,doctor_id',
            'workingHours:id,doctor_id,day_of_week,opening_time,closing_time,is_closed'
        ]);

        $doctors = Doctor::with(['user' , 'specialization' , 'clinic' , 'workingHours'])

        ->select('id','user_id','spelization_id','location','is_verified')
        ->where('is_verified', true)
        
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {

                $q->whereHas('user', function ($srch) use ($request) {
                    $srch->where('name', 'like', "%{$request->search}%");
                })

                // OR البحث باسم التخصص
                ->orWhereHas('spelization', function ($sp) use ($request) {
                    $sp->where('name', 'like', "%{$request->search}%");
                })

                ->orWhere('location' ,  'like' , "%{$request->search}%")

                ->orWhereHas('workingHours', function ($hour) use ($request) {
                    $hour->where('day_of_week', $request->search)
                    ->where('is_closed', false);
                });
            });
        })

        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Doctor Is Found',
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
        $reviews = Review::with(['patient.user' => function($query)
        {
            $query->select('id' , 'name');
        }])
        
        ->where('doctor_id' , $id)
        ->paginate();

        return response()->json([
            'status' => true,
            'message' => 'Patients Reviews',
            'data' => $reviews,
        ] , 200);
    }

    // public function createWorkingDays(Request $request)
    // {
    //     $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

    //     foreach ($days as $day) {
    //         DoctorWorking::create([
    //             'doctor_id' => 1,
    //             'day_of_week' => $day,
    //             'opening_time' => '05:00:00',
    //             'closing_time' => '10:00:00',
    //             'is_closed' => 0,
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Working days created successfully'
    //     ]);
    // }

    public function workingHours(Request $request , $id)
    {
        $hours = DoctorWorking::with(['doctor.user' => function($query)
        {
            $query->select('id' , 'name');
        }])

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
        $onlineHours = DoctorWorking::with(['doctor.user' => function($query)
        {
            $query->select('id' , 'name');
        }])

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