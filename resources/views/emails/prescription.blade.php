<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .footer { background-color: #f8f9fa; padding: 10px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Xet Specialized Hospital</h1>
        </div>
        <div class="content">
            <p>Dear {{ $appointment->first_name }} {{ $appointment->last_name }},</p>
            <p>Your doctor has issued a prescription for your appointment (Booking ID: {{ $appointment->booking_id }}).</p>
            <p>Please find the prescription details attached as a PDF.</p>
            <p>If you have any questions, please contact us.</p>
            <p>Best regards,Xet Specialized Hospital<br></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
