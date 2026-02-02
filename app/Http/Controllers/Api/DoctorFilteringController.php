<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
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
        $request->validate([
        'search' => 'required|string',
        ]);

        $doctors = Doctor::search($request->search)
            ->query(function ($query) {
                $query->with(['user:id,name,email', 'spelization:id,name']);
            })
            ->paginate(10);

        if ($doctors->isEmpty()) {

            return ApiResponse::error(404 , 'No Doctors Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Search' , $doctors);
        }

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

        if (!$doctor) {

            return ApiResponse::error(404 , 'Doctors Informations Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Informations' , $doctor);
        }
    }


    public function createWorkingDays(Request $request)
    {
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        foreach ($days as $day) {
            DoctorWorking::create([
                'doctor_id' => 1,
                'day_of_week' => $day,
                'opening_time' => '05:00:00',
                'closing_time' => '10:00:00',
                'is_closed' => 0,
            ]);
        }

        return ApiResponse::sendResponse(200 , 'Doctors Working Days Added Successfully');
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


        if (!$hours) {

            return ApiResponse::error(404 , 'Doctors Working Hours Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Working Hours Is Found' , $hours);
        }
    }


    public function workingHoursOnline($id)
    {
        $onlineHours = DoctorWorkingHoursOnline::with(['doctor.user' => function($query)
        {
            $query->select('id' , 'name');
        }])

        ->where('doctor_id', $id)
        ->orderByRaw("FIELD(day_of_week,
            'Monday','Tuesday','Wednesday',
            'Thursday','Friday','Saturday','Sunday')")
        ->get();

        if (!$onlineHours) {

            return ApiResponse::error(404 , 'Doctors Online Working Hours Are Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Online Working Hours Are Found' , $onlineHours);
        }
    }
}