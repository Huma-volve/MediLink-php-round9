<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    public function index()
    {
        // check if this appointment belongs to the authenticated doctor
        $doctor = $this->getAuthenticatedDoctor();

        // get doctor appointments
        $appointments = $this->appointmentService->getDoctorAppointments($doctor);

        return ApiResponse::sendResponse(
            200,
            'Doctor appointments fetched successfully',
            AppointmentResource::collection($appointments)
        );
    }

    public function confirmAppointment($appointmentId)
    {
        $doctor = $this->getAuthenticatedDoctor();
        $appointment = Appointment::findOrFail($appointmentId);

        $confirmedAppointment = $this->appointmentService->confirmAppointment($appointment, $doctor);

        if (!$confirmedAppointment) {
            return ApiResponse::sendResponse(
                400,
                'Appointment cannot be confirmed',
                null
            );
        }

        return ApiResponse::sendResponse(
            200,
            'Appointment confirmed successfully',
            new AppointmentResource($confirmedAppointment)
        );
    }

    public function cancelAppointment($appointmentId)
    {
        $doctor = $this->getAuthenticatedDoctor();
        $appointment = Appointment::findOrFail($appointmentId);

        $cancelledAppointment = $this->appointmentService->cancelAppointment($appointment, $doctor);

        if (!$cancelledAppointment) {
            return ApiResponse::sendResponse(
                400,
                'Appointment cannot be cancelled',
                null
            );
        }

        return ApiResponse::sendResponse(
            200,
            'Appointment cancelled successfully',
            new AppointmentResource($cancelledAppointment)
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
