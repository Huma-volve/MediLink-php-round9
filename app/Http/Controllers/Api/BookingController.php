<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use App\Models\Appointment;
use App\Models\DoctorWorking;
use App\Helper\ApiResponse;
use App\Http\Resources\DoctorWorkingResource;
use App\Models\Doctor;
use App\Models\DoctorWorkingHoursOnline;
use Carbon\Carbon;

class BookingController extends Controller
{

    // show the doctor schedules 
    public function doctorSchedules(Request $request, string $id)
    {
        $appointment_type = $request->appointment_type;

        if ($appointment_type == 'online') {

            $doctorOnlineHours = DoctorWorkingHoursOnline::where('doctor_id', $id)
                ->where('is_closed', 0)->paginate(10);

            $data = [
                'doctor hours' => DoctorWorkingResource::collection($doctorOnlineHours)
            ];
        } else if ($appointment_type == 'in_person') {

            $doctorHours = DoctorWorking::where('doctor_id', $id)
                ->where('is_closed', 0)->paginate(10);

            $data = [
                'doctor hours' => DoctorWorkingResource::collection($doctorHours)
            ];
        } else {
            return ApiResponse::sendResponse(
                422,
                'Invalid appointment type',
                null
            );
        }
        return ApiResponse::sendResponse(
            200,
            'all doctor schedules',
            $data
        );
    }

    // Get available time slots for a doctor on a given date
    public function getSlots(Request $request, string $id)
    {
        $appointment_type = $request->appointment_type;
        $dayName = Carbon::parse($request->date)->format('l');

        if ($appointment_type == 'online') {
            $schedule = DoctorWorkingHoursOnline::where('doctor_id', $id)->where('day_of_week', $dayName)
                ->where('is_closed', 0)
                ->first();
        } else if ($appointment_type == 'in_person') {
            $schedule = DoctorWorking::where('doctor_id', $id)->where('day_of_week', $dayName)
                ->where('is_closed', 0)
                ->first();
        }

        if (!$schedule) {
            return ApiResponse::sendResponse(
                404,
                'doctor is not available in this day',
                null
            );
        }

        $slotDuration = 30;
        $start = Carbon::parse($schedule->opening_time);
        $end = Carbon::parse($schedule->closing_time);

        $slots = [];
        $doctor = Doctor::findOrFail($id);

        while ($start->copy()->addMinutes($slotDuration) <= $end) {

            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes($slotDuration);

            $exists = $doctor->appointments()
                ->whereDate('appointment_date', $request->date)
                ->whereTime('appointment_time', '>=', $slotStart->format('H:i'))
                ->whereTime('appointment_time', '<', $slotEnd->format('H:i'))
                ->whereIn('status', ['pending', 'paid', 'upcoming', 'booked'])
                ->exists();

            if (!$exists) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end'   => $slotEnd->format('H:i'),
                ];
            }

            $start->addMinutes($slotDuration);
        }

        return ApiResponse::sendResponse(
            200,
            'available slots for this doctor',
            $slots
        );
    }


    // book appointment 
    public function store(BookingRequest $request, string $id)
    {
        $user_id = auth()->id();
        $patient = Patient::where('user_id', $user_id)->first();
        if (!$patient) {
            return ApiResponse::sendResponse(
                403,
                'Only patients can book appointments',
                null
            );
        }

        $appointmentTime = Carbon::parse($request->appointment_time);

        if ($appointmentTime->minute % 30 !== 0) {
            return ApiResponse::sendResponse(
                422,
                'Invalid slot time',
                null
            );
        }

        $dayName = Carbon::parse($request->appointment_date)->format('l');
        $doctor = Doctor::findOrFail($id);
        $consultation_type = $request->consultation_type;

        if ($consultation_type == 'in_person') {

            $schedule = $doctor->workingHours()
                ->where('day_of_week', $dayName)
                ->where('is_closed', 0)
                ->first();
        } else if ($consultation_type == 'online') {

            $schedule = $doctor->workingHoursOnline()
                ->where('day_of_week', $dayName)
                ->where('is_closed', 0)
                ->first();
        } else {
            return ApiResponse::sendResponse(
                422,
                'Invalid consultation type',
                null
            );
        }


        if (!$schedule) {
            return ApiResponse::sendResponse(
                404,
                'Doctor is not available on this day',
                null
            );
        }

        if (
            $appointmentTime->lt($schedule->opening_time) ||
            $appointmentTime->gte($schedule->closing_time)
        ) {

            return ApiResponse::sendResponse(
                422,
                'Appointment time is outside doctor working hours',
                null
            );
        }

        $exists = Appointment::where('doctor_id', $id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'paid', 'upcoming', 'booked'])
            ->exists();

        if ($exists) {
            return ApiResponse::sendResponse(
                409,
                'This slot is already booked',
                null
            );
        }

        $alreadyBooked = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->exists();

        if ($alreadyBooked) {
            return ApiResponse::sendResponse(
                409,
                'You already have an appointment with this doctor',
                null
            );
        }
        $validated = $request->validated();

        $appointment = $patient->appointments()->create(array_merge($validated, [
            'status' => 'pending',
            'doctor_id' => $doctor->id,
        ]));

        return ApiResponse::sendResponse(
            201,
            'Appointment booked and awaiting confirmation',
            $appointment
        );
    }

    // confirm booking 
    public function confirm(string $id)
    {
        $user_id = auth()->id();
        $patient = Patient::where('user_id', $user_id)->first();
        if (!$patient) {
            return ApiResponse::sendResponse(
                403,
                'Only patients allowed',
                null
            );
        }

        $exists = $patient->appointments()
            ->where('id', $id)->exists();

        if (!$exists) {
            return ApiResponse::sendResponse(
                403,
                'Patient is not authorized to confirm this appointment',
                null
            );
        }

        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'upcoming'
        ]);

        return ApiResponse::sendResponse(
            201,
            'Appointment booked successfully',
            $appointment
        );
    }

    // cancel appointment 
    public function cancel(string $id)
    {
        $user_id = auth()->id();
        $patient = Patient::where('user_id', $user_id)->first();

        if (!$patient) {
            return ApiResponse::sendResponse(
                403,
                'Only patients allowed',
                null
            );
        }
        $exists = $patient->appointments()
            ->where('id', $id)->exists();

        if (!$exists) {
            return ApiResponse::sendResponse(
                403,
                'Patient is not authorized to cancel this appointment',
                null
            );
        }

        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'status' => 'cancelled'
        ]);

        return ApiResponse::sendResponse(
            200,
            'Appointment cancelled successfully',
            null
        );
    }

    // show all patient appointments
    public function showAppointments(Request $request)
    {
        $status = $request->status;
        $user_id = auth()->id();
        $patient = Patient::where('user_id', $user_id)->first();

        if (!$patient) {
            return ApiResponse::sendResponse(
                403,
                'Only patients allowed',
                null
            );
        }

        if ($status == 'completed') {
            $patient_appointments = Appointment::where('patient_id', $patient->id)
                ->where('status', 'completed')
                ->get();
        } else if ($status == 'upcoming') {
            $patient_appointments = Appointment::where('patient_id', $patient->id)
                ->where('status', 'upcoming')
                ->get();
        } else if ($status == 'cancelled') {
            $patient_appointments = Appointment::where('patient_id', $patient->id)
                ->where('status', 'cancelled')
                ->get();
        } else {
            return ApiResponse::sendResponse(
                422,
                'invalid status',
                null
            );
        }

        return ApiResponse::sendResponse(
            200,
            null,
            $patient_appointments
        );
    }
}