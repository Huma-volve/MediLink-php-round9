<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use App\Models\Appointment;
use App\Models\DoctorWorking;
use App\Helper\ApiResponse;
use App\Http\Resources\AppointmentDetailResource;
use App\Http\Resources\DoctorWorkingResource;
use App\Http\Resources\PatientAppointmentResource;
use App\Models\Doctor;
use App\Models\DoctorWorkingHoursOnline;
use Carbon\Carbon;

class BookingController extends Controller
{
    private function getAuthenticatedPatient()
    {
        $patient = Patient::where('user_id', auth()->id())->first();

        if (!$patient) {
            abort(403, 'Only patients allowed');
        }
        return $patient;
    }

    // show the doctor schedules 
    public function getDoctorSchedules(Request $request, string $id)
    {
        $appointment_type = $request->appointment_type;

        if ($appointment_type == 'online') {

            $doctorOnlineHours = DoctorWorkingHoursOnline::where('doctor_id', $id)
                ->where('is_closed', 0)->get();

            $data = [
                'doctor hours' => DoctorWorkingResource::collection($doctorOnlineHours)
            ];
        } else if ($appointment_type == 'in_person') {

            $doctorHours = DoctorWorking::where('doctor_id', $id)
                ->where('is_closed', 0)->get();

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
        $doctor = Doctor::findOrFail($id);
        $appointment_type = $request->appointment_type;
        $dayName = Carbon::parse($request->date)->format('l');

        $scheduleRelation = $appointment_type === 'online'
            ? 'workingHoursOnline'
            : 'workingHours';

        $schedule = $doctor->$scheduleRelation()
            ->where('day_of_week', $dayName)
            ->where('is_closed', 0)
            ->first();


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
        $patient = $this->getAuthenticatedPatient();
        $dayName = Carbon::parse($request->appointment_date)->format('l');
        $doctor = Doctor::findOrFail($id);
        $consultation_type = $request->consultation_type;

        $scheduleRelation = $consultation_type === 'online'
            ? 'workingHoursOnline'
            : 'workingHours';

        $schedule = $doctor->$scheduleRelation()
            ->where('day_of_week', $dayName)
            ->where('is_closed', 0)
            ->first();

        if (!$schedule) {
            return ApiResponse::sendResponse(
                404,
                'Doctor is not available on this day',
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

        $validated = $request->validated();
        $booking_type = $validated['booking_type'];


        if ($booking_type == 'myself') {
            $validated['patient_name'] = $patient->name;
            $validated['patient_email'] = $patient->email;
            $validated['patient_phone'] = $patient->phone;
        }
        unset($validated['booking_type']);

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
        $patient = $this->getAuthenticatedPatient();

        $appointment = $patient->appointments()->find($id);

        if (!$appointment) {
            return ApiResponse::sendResponse(
                403,
                'Patient is not authorized to confirm this appointment',
                null
            );
        }

        $appointment->update([
            'status' => 'upcoming'
        ]);

        $data = [
            'appointment details' => new AppointmentDetailResource($appointment)
        ];
        return ApiResponse::sendResponse(
            201,
            'Appointment booked successfully',
            $data
        );
    }

    // cancel appointment 
    public function cancel(string $id)
    {
        $patient = $this->getAuthenticatedPatient();
        $appointment = $patient->appointments()->find($id);

        if (!$appointment) {
            return ApiResponse::sendResponse(
                403,
                'Patient is not authorized to cancel this appointment',
                null
            );
        }

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
        $patient = $this->getAuthenticatedPatient();
        $status = $request->status;

        $allowedStatuses = ['completed', 'upcoming', 'cancelled'];

        if (!in_array($status, $allowedStatuses)) {
            return ApiResponse::sendResponse(422, 'Invalid status', null);
        }

        $patient_appointments = Appointment::where('patient_id', $patient->id)
            ->where('status', $status)
            ->get();

        $data = [
            'my_appointments' => PatientAppointmentResource::collection($patient_appointments)
        ];

        return ApiResponse::sendResponse(
            200,
            null,
            $data
        );
    }
}
