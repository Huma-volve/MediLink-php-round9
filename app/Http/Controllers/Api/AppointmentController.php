<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        // check if this appointment belongs to the authenticated doctor
        $doctor = $this->getAuthenticatedDoctor();

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient.user'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        return ApiResponse::sendResponse(
            200,
            'Doctor appointments fetched successfully',
            AppointmentResource::collection($appointments)
        );
    }

    public function confirmAppointment($appointment)
    {
        $doctor = $this->getAuthenticatedDoctor();

        $appointment = Appointment::findOrFail($appointment);

        // check if the appointment does not belong to this doctor
        if ($appointment->doctor_id !== $doctor->id) {
            return ApiResponse::sendResponse(
                400,
                'Appointment does not belong to this doctor',
                null
            );
        }

        // Check if already completed or cancelled or upcoming
        if (
            $appointment->status === Appointment::STATUS_COMPLETED ||
            $appointment->status === Appointment::STATUS_CANCELLED ||
            $appointment->status === Appointment::STATUS_UPCOMING
        ) {
            return ApiResponse::sendResponse(
                400,
                'Appointment cannot be confirmed',
                null
            );
        }

        // Check if the appointment date is in the past
        if ($appointment->appointment_date < now()->toDateString()) {
            return ApiResponse::sendResponse(
                400,
                'Appointment cannot be confirmed',
                null
            );
        }

        $appointment->update([
            'status' => Appointment::STATUS_UPCOMING,
        ]);

        return ApiResponse::sendResponse(
            200,
            'Appointment confirmed successfully',
            new AppointmentResource($appointment)
        );
    }

    public function cancelAppointment($appointment)
    {

        // check if this appointment belongs to the authenticated doctor
        $doctor = $this->getAuthenticatedDoctor();

        $appointment = Appointment::findOrFail($appointment);

        // check if the appointment does not belong to this doctor
        if ($appointment->doctor_id !== $doctor->id) {
            return ApiResponse::sendResponse(
                400,
                'Appointment does not belong to this doctor',
                null
            );
        }

        // Check if already cancelled or completed
        if (
            $appointment->status === Appointment::STATUS_CANCELLED ||
            $appointment->status === Appointment::STATUS_COMPLETED
        ) {
            return ApiResponse::sendResponse(
                400,
                'Appointment already cancelled or completed',
                null
            );
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
        ]);

        return ApiResponse::sendResponse(
            200,
            'Appointment cancelled successfully',
            new AppointmentResource($appointment)
        );
    }





    protected function getAuthenticatedDoctor()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(ApiResponse::sendResponse(403, 'Unauthorized', null));
        }

        return $doctor;
    }
}
