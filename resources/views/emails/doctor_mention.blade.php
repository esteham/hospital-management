@component('mail::message')

<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #2c5530; margin-bottom: 10px; font-size: 28px;">New Administrative Message</h1>
    <div style="height: 3px; width: 60px; background: #2c5530; margin: 0 auto;"></div>
</div>

<p style="color: #555; font-size: 16px; line-height: 1.6;">Dear Dr. {{ $doctor->user->name }},</p>

<p style="color: #555; font-size: 16px; line-height: 1.6;">You have received an important administrative message regarding your schedule.</p>

@if($schedule)
<div style="background: #f8f9fa; border-left: 4px solid #2c5530; padding: 20px; margin: 25px 0; border-radius: 4px;">
    <h3 style="color: #2c5530; margin-top: 0; margin-bottom: 15px;">Schedule Information</h3>
    <div style="display: grid; gap: 8px;">
        <div style="display: flex;">
            <span style="min-width: 80px; color: #666; font-weight: 500;">Day:</span>
            <span style="color: #333;">{{ ucfirst($schedule->day_of_week) }}</span>
        </div>
        <div style="display: flex;">
            <span style="min-width: 80px; color: #666; font-weight: 500;">Time:</span>
            <span style="color: #333;">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
        </div>
        <div style="display: flex;">
            <span style="min-width: 80px; color: #666; font-weight: 500;">Fee:</span>
            <span style="color: #333;">${{ $schedule->fee }}</span>
        </div>
    </div>
</div>
@endif

<div style="background: #fff; border: 1px solid #e0e0e0; padding: 20px; margin: 25px 0; border-radius: 8px;">
    <h3 style="color: #2c5530; margin-top: 0; margin-bottom: 15px;">Message Content</h3>
    <p style="color: #555; font-size: 15px; line-height: 1.6; margin: 0; white-space: pre-line;">{{ $message->message }}</p>
</div>

<div style="text-align: center; margin: 30px 0;">
    @component('mail::button', ['url' => url('/login'), 'color' => 'success'])
        Access Physician Portal
    @endcomponent
</div>

<div style="border-top: 1px solid #e0e0e0; padding-top: 20px; margin-top: 20px;">
    <p style="color: #666; font-size: 14px; line-height: 1.5; margin-bottom: 10px;">
        For any questions or concerns, please contact our administrative team through your dashboard.
    </p>
</div>

<p style="color: #666; font-size: 15px; margin-top: 25px;">
    Best regards,<br>
    <strong style="color: #2c5530;">{{ config('app.name') }} Medical Team</strong>
</p>

@endcomponent