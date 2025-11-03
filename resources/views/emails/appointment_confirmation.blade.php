<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .appointment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .booking-id {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Appointment Confirmed</h1>
        <p>Your appointment has been successfully booked</p>
    </div>

    <div class="content">
        <p>Dear {{ $appointment->first_name }} {{ $appointment->last_name }},</p>

        <p>Thank you for booking an appointment with Xet Specialized Hospital. Your appointment details are confirmed and attached to this email as a PDF for your records.</p>

        <div class="appointment-details">
            <div class="booking-id">Booking ID: {{ $appointment->booking_id }}</div>
            <p><strong>Patient Name:</strong> {{ $appointment->first_name }} {{ $appointment->last_name }}</p>
            <p><strong>Email:</strong> {{ $appointment->email }}</p>
            <p><strong>Phone:</strong> {{ $appointment->phone }}</p>
            <p><strong>Preferred Date:</strong> {{ \Carbon\Carbon::parse($appointment->preferred_date)->format('F j, Y') }}</p>
            <p><strong>Preferred Time:</strong> {{ $appointment->preferred_time }}</p>
            <p><strong>Speciality:</strong> {{ $appointment->speciality }}</p>
            @if($appointment->doctor_name)
            <p><strong>Doctor:</strong> {{ $appointment->doctor_name }}</p>
            @endif
            @if($appointment->additional_notes)
            <p><strong>Additional Notes:</strong> {{ $appointment->additional_notes }}</p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
        </div>

        <p>Please keep this booking ID for your reference. You can use it to track your appointment status or make any changes.</p>

        <p>If you have any questions or need to reschedule, please contact us at appointments@xethospital.com or call us at 999.</p>

        <p>We look forward to serving you!</p>

        <p>Best regards,<br>
        Xet Specialized Hospital Team</p>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</p>
    </div>
</body>
</html>
