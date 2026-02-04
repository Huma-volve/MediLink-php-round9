<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorWorking;
use App\Models\DoctorWorkingHoursOnline;
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
                $query->with(['user:id,name,email', 'specialization:id,name']);
            })
            ->paginate(10);

        if ($doctors->isEmpty()) {

            return ApiResponse::error(404 , 'No Doctors Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Search' , $doctors);
        }
    }

    public function doctorsInformation($id)
    {
        $doctor = Doctor::with([
            'user:id,name,email,phone',
            'clinic:id,name,address',
            'specialization:id,name'
        ])->findOrFail($id);

        if ($doctor->isEmpty()) {

            return ApiResponse::error(404 , 'Doctors Informations Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Informations' , $doctor);
        }
    }


    public function createWorkingDays(Request $request)
    {
        $request->validate([
            'doctor_workings' => 'required|array',
            'doctor_workings.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'doctor_workings.*.opening_time' => 'required|date_format:H:i',
            'doctor_workings.*.closing_time' => 'required|date_format:H:i|after:working_days.*.opening_time',
            'doctor_workings.*.is_closed' => 'required|boolean',
        ]);

        $doctor = $request->user()->doctor;

        foreach ($request->doctor_workings as $day) {

            DoctorWorking::updateOrCreate(
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day['day_of_week'],
                ],
                [
                    'opening_time' => $day['opening_time'],
                    'closing_time' => $day['closing_time'],
                    'is_closed' => $day['is_closed'],
                ]
            );
        }

        return ApiResponse::sendResponse(200 , 'Doctors Working Days Added Successfully');
    }

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


        if ($hours->isEmpty()) {

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
        ->get()
        
        ->map(function($item) {
            return array_merge($item->toArray(), ['online' => true]);
        });

        if ($onlineHours->isEmpty()) {

            return ApiResponse::error(404 , 'Doctors Online Working Hours Are Not Found');

        } else {

            return ApiResponse::sendResponse(200 , 'Doctors Online Working Hours Are Found' , $onlineHours);
        }
    }
}