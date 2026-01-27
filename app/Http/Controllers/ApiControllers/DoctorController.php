<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorWorking;
use App\Models\Review;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::with(['user' , 'spelization' , 'clinic' , 'doctor_workings'])

        ->when($request->search, function ($query) use ($request) {

            $query->where(function ($q) use ($request) {

                $q->whereHas('user', function ($srch) use ($request) {
                    $srch->where('full_name', 'like', "%{$request->search}%");
                })

                ->orWhereHas('spelization', function ($filter) use ($request) {
                    $filter->where('name', 'like', "%{$request->search}%");
                })

                ->orWhereHas('clinic', function ($cli) use ($request) {
                    $cli->where('name', 'like', "%{$request->search}%")
                        ->orWhere('address', 'like', "%{$request->search}%");
                })

                ->orWhereHas('doctor_workings', function ($work) use ($request) {
                    $work->where('is_closed', $request->search == 'closed' ? true : false);
                });
            });
        })
        ->get();

        // return response()->json($doctors);

        if ($doctors->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No doctors found',
                'data' => []
            ]);
        }
    }
}
