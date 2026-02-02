<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'sans-serif';
            direction: rtl;
        }

        .header {
            border-bottom: 2px solid #2D6A4F;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .doctor-info {
            float: right;
            width: 50%;
        }

        .patient-info {
            float: left;
            width: 50%;
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .content {
            margin-top: 30px;
            border: 1px solid #eee;
            padding: 20px;
            min-height: 300px;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }

        .rx {
            font-size: 24px;
            color: #2D6A4F;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 style="color: #2D6A4F;">MediLink Medical Center</h1>
        <p>Your Health, Our Priority</p>
    </div>

    <div class="doctor-info">
        <strong>Doctor:</strong> {{ $appointment->doctor->name }} <br>
        <strong>Specialization:</strong> {{ $appointment->doctor->specialization }}
    </div>

    <div class="patient-info">
        <strong>Patient:</strong> {{ $appointment->patient->user->name }} <br>
        <strong>Date:</strong> {{ $appointment->appointment_date }}
    </div>

    <div class="clear"></div>

    <div class="content">
        <span class="rx">R/</span>
        <p style="margin-top: 20px;">
            {!! nl2br(e($appointment->prescription_notes)) !!}
        </p>
    </div>

    <div class="footer">
        This is an electronically generated prescription. No signature required. <br>
        Address: 123 Health St, Medical City
    </div>
</body>

</html>
