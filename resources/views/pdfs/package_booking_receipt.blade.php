<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Package Booking Receipt — {{ $packageBooking->id }}</title>
    <style>
        /* ----------------------
           PDF / Print Defaults
        ----------------------- */
        @page { size: A4; margin: 22mm 18mm; }
        html, body { height: 100%; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: #0f172a; /* slate-900 */
            line-height: 1.55;
            font-size: 12.5px;
            margin: 0;
            background: #fff;
        }
        * { box-sizing: border-box; }
        .container { position: relative; z-index: 0; }

        /* Watermark (Logo) */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-12deg);
            opacity: .06;
            z-index: 0;
            width: 70%;
            text-align: center;
            filter: grayscale(100%);
            pointer-events: none;
        }
        .watermark img { max-width: 100%; height: auto; }

        /* Header / Footer fixed for one-page layout */
        .header {
            display: flex; align-items: flex-start; justify-content: space-between;
            border-bottom: 1px solid #e2e8f0; /* slate-200 */
            padding-bottom: 10px; margin-bottom: 16px;
            position: relative; z-index: 2;
        }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand-logo {
            width: 40px; height: 40px; object-fit: contain;
        }
        .brand-title { font-weight: 800; letter-spacing: .2px; color: #1d4ed8; /* blue-700 */ }
        .brand-subtitle { color: #64748b; font-size: 10.5px; }

        .meta {
            text-align: right;
            font-size: 11px;
            color: #334155; /* slate-700 */
        }

        /* QR Code in top right */
        .qr-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 14px;
            height: 14px;
            overflow: hidden;
        }
        .qr-container .qr-code {
            width: 14px;
            height: 14px;
            object-fit: contain;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 10.5px; font-weight: 700;
            border: 1px solid currentColor;
            letter-spacing: .3px;
        }
        .badge--confirmed { color: #16a34a; }  /* green-600 */
        .badge--pending   { color: #d97706; }  /* amber-600 */
        .badge--cancelled { color: #dc2626; }  /* red-600 */

        /* Booking strip */
        .booking {
            background: #f8fafc; /* slate-50 */
            border: 1px solid #e2e8f0; /* slate-200 */
            border-radius: 10px; padding: 10px 12px; margin: 10px 0 18px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px;
        }
        .booking .id { font-weight: 800; color: #1d4ed8; letter-spacing:.3px; }
        .booking .small { color: #64748b; font-size: 11px; }

        /* Two-column layout for patient and booking info */
        .info-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 16px;
        }

        /* Sections */
        .section { margin-bottom: 16px; position: relative; z-index: 1; }
        .section-title {
            font-weight: 800; font-size: 12.5px; color: #1d4ed8;
            border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 10px;
            letter-spacing: .2px;
        }

        /* Grid details */
        .grid { display: grid; grid-template-columns: 135px 1fr; row-gap: 7px; column-gap: 14px; }
        .label { font-weight: 700; color: #0f172a; }
        .value { color: #334155; }
        .muted { color: #64748b; }

        /* Info note */
        .note {
            background: #fffbeb; /* amber-50 */
            border: 1px solid #fde68a; /* amber-300 */
            padding: 10px 12px; border-radius: 10px; font-size: 11.5px; color: #713f12; /* amber-900 */
            margin: 14px 0;
        }

        /* Contact row */
        .contact-card {
            border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px;
            background: #ffffffcc; /* slight white for over watermark */
        }

        /* Footer */
        .footer {
            margin-top: 16px; padding-top: 10px;
            border-top: 1px solid #e2e8f0; text-align: center; color: #64748b; font-size: 10.5px;
            position: relative; z-index: 2;
        }
        .nowrap { white-space: nowrap; }

        /* Keep to one page: prevent awkward breaks */
        .section, .booking, .info-columns { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $status = strtolower($packageBooking->status ?? 'pending');
    $badgeClass = [
        'confirmed' => 'badge badge--confirmed',
        'pending'   => 'badge badge--pending',
        'cancelled' => 'badge badge--cancelled',
    ][$status] ?? 'badge badge--pending';
@endphp

<div class="watermark">
    <img src="{{ $watermarkLogoPath ?? (isset($logoPath) ? $logoPath : asset('images/logo.png')) }}" alt="Xet Specialized Hospital" />
</div>

<div class="container">
    <!-- Header -->
    <div class="header">
        <div class="brand">
            <img class="brand-logo" src="{{ $logoPath ?? asset('images/logo.png') }}" alt="Xet Logo" />
            <div>
                <div class="brand-title">Xet Specialized Hospital</div>
                <div class="brand-subtitle">Package Booking Receipt • Official Document</div>
            </div>
        </div>
        <div class="meta">
            <div><strong>Date:</strong> {{ now()->format('F j, Y') }}</div>
            <div><strong>Time:</strong> {{ now()->format('g:i A') }}</div>
            <div><span class="{{ $badgeClass }}">{{ ucfirst($status) }}</span></div>
        </div>

        <!-- QR Code in top right corner -->
        <div class="qr-container">
            @if(!empty($qrSvg))
                {!! $qrSvg !!}
            @elseif(class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode'))
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(14)->generate($packageBooking->id) !!}
            @else
                <div class="qr-code" style="border:1px dashed #cbd5e1; padding:2px; text-align:center; font-size:6px;">
                    QR
                </div>
            @endif
        </div>
    </div>

    <!-- Booking Strip -->
    <div class="booking">
        <div>
            <div class="small">Booking ID</div>
            <div class="id">{{ $packageBooking->id }}</div>
        </div>
        <div class="small">Please bring a valid photo ID and arrive 15 minutes early.</div>
    </div>

    <!-- Two-column layout for Patient Information and Booking Details -->
    <div class="info-columns">
        <!-- Patient Information -->
        <div class="section">
            <div class="section-title">Patient Information</div>
            <div class="grid">
                <div class="label">Full Name</div>
                <div class="value">{{ $packageBooking->user->name }}</div>

                <div class="label">Email</div>
                <div class="value">{{ $packageBooking->user->email }}</div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="section">
            <div class="section-title">Booking Details</div>
            <div class="grid">
                <div class="label">Package</div>
                <div class="value">{{ $packageBooking->healthCheck->name }}</div>

                <div class="label">Payment Type</div>
                <div class="value">{{ $packageBooking->payment_type }}</div>

                <div class="label">Amount Paid</div>
                <div class="value">{{ $packageBooking->amount_paid }}</div>

                <div class="label">Total Amount</div>
                <div class="value">{{ $packageBooking->total_amount }}</div>

                <div class="label">Status</div>
                <div class="value">{{ ucfirst($packageBooking->status) }}</div>

                <div class="label">Booked On</div>
                <div class="value">{{ \Carbon\Carbon::parse($packageBooking->created_at)->format('F j, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Important Note -->
    <div class="note">
        <strong>Important:</strong> Please carry this receipt (printed or digital) and a valid ID. Reach the reception 15 minutes prior to your slot for registration.
    </div>

    <!-- Contact Card -->
    <div class="contact-card">
        <div class="section-title" style="border:0;margin:0 0 8px 0;">Contact Information</div>
        <div class="grid" style="grid-template-columns: 95px 1fr;">
            <div class="label">Email</div>
            <div class="value">appointments@xethospital.com</div>
            <div class="label">Hotline</div>
            <div class="value">999</div>
            <div class="label">Website</div>
            <div class="value">xethospital.com</div>
            <div class="label">Location</div>
            <div class="value">Xet Specialized Hospital, Dhaka</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Document generated on {{ now()->format('F j, Y \a\t g:i A') }} <span class="nowrap">(Booking: {{ $packageBooking->id }})</span></div>
        <div>&copy; {{ date('Y') }} Xet Specialized Hospital. All rights reserved.</div>
    </div>
</div>
</body>
</html>
