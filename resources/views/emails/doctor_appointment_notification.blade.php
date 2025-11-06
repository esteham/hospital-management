<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Appointment Notification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .appointment-details { background-color: white; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Appointment Booked</h1>
        </div>
        <div class="content">
            <p>Dear Doctor,</p>
            <p>A new appointment has been booked with you. Here are the details:</p>
            <div class="appointment-details">
                <h3>Appointment Details</h3>
                <p><strong>Booking ID:</strong> {{ $appointment->booking_id }}</p>
                <p><strong>Patient Name:</strong> {{ $appointment->first_name }} {{ $appointment->last_name }}</p>
                <p><strong>Email:</strong> {{ $appointment->email }}</p>
                <p><strong>Phone:</strong> {{ $appointment->phone }}</p>
                <p><strong>Preferred Date:</strong> {{ $appointment->preferred_date }}</p>
                <p><strong>Preferred Time:</strong> {{ $appointment->preferred_time }}</p>
                <p><strong>Speciality:</strong> {{ $appointment->speciality }}</p>
                @if($appointment->additional_notes)
                <p><strong>Additional Notes:</strong> {{ $appointment->additional_notes }}</p>
                @endif
            </div>
            <p>Please log in to your dashboard to view more details and manage the appointment.</p>
            <p>Best regards,<br>Xet Specialized Hospital Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
