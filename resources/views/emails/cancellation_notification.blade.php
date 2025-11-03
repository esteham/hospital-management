<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <meta name="supported-color-schemes" content="light dark">
  <title>Package Booking Update</title>
  <style>
    /* Base resets */
    body,table,td,div,p,a { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
    body { margin:0; padding:0; width:100% !important; }
    img { border:0; outline:none; text-decoration:none; -ms-interpolation-mode:bicubic; display:block; }
    a { text-decoration:none; }
    /* Container */
    .wrapper { width:100%; background:#f4f6f8; }
    .outer { width:100%; max-width:640px; margin:0 auto; }
    .card { background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 2px 8px rgba(16,24,40,.06); }
    .px { padding-left:24px; padding-right:24px; }
    .py { padding-top:24px; padding-bottom:24px; }
    .mx { margin-left:auto; margin-right:auto; }
    .text { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", sans-serif; color:#0f172a; line-height:1.55; }
    .muted { color:#64748b; }
    .h1 { margin:0; font-size:22px; font-weight:700; letter-spacing:.2px; }
    .h2 { margin:0 0 6px 0; font-size:16px; font-weight:700; }
    .p  { margin:0 0 14px 0; font-size:15px; }
    .badge { display:inline-block; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600; background:#eef2ff; color:#3730a3; }
    /* CTA (bulletproof) */
    .btn { display:inline-block; background:#3b82f6; color:#ffffff !important; border-radius:10px; padding:12px 18px; font-weight:600; font-size:15px; }
    .btn:hover { opacity:.92; }
    /* Info list */
    .kvs td { padding:6px 0; font-size:14px; }
    .kvs .k { color:#475569; width:140px; }
    .kvs .v { color:#0f172a; }
    /* Footer */
    .foot { color:#94a3b8; font-size:12px; }
    /* Responsive */
    @media (max-width: 600px){
      .px { padding-left:16px !important; padding-right:16px !important; }
      .py { padding-top:18px !important; padding-bottom:18px !important; }
      .h1 { font-size:20px !important; }
    }
    /* Dark mode */
    @media (prefers-color-scheme: dark){
      .wrapper { background:#0b1220 !important; }
      .card { background:#121a2b !important; box-shadow:none !important; }
      .text { color:#e5e7eb !important; }
      .muted { color:#9aa4b2 !important; }
      .kvs .k { color:#cbd5e1 !important; }
      .kvs .v { color:#f3f4f6 !important; }
      .badge { background:#1f2937 !important; color:#93c5fd !important; }
      .foot { color:#9aa4b2 !important; }
    }
  </style>
</head>
<body class="text" style="background:#f4f6f8;">
  <!-- Preheader (hidden preview text) -->
  <div style="display:none;opacity:0;max-height:0;overflow:hidden;line-height:1px;">
    Your package booking has been updated. View details inside.
  </div>

  <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="wrapper">
    <tr>
      <td align="center" style="padding:28px 16px;">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="outer">
          <!-- Brand header -->
          <tr>
            <td class="mx" style="padding-bottom:16px;">
              <table width="100%" role="presentation" cellpadding="0" cellspacing="0" class="text">
                <tr>
                  <td align="left" style="font-size:14px;color:#64748b;">
                    <img src="{{ asset('images/logo.png') }}" width="44" height="44" alt="Hospital Logo" style="border-radius:10px;">
                  </td>
                  <td align="right" style="font-size:13px;color:#94a3b8;">
                    {{ date('F j, Y') }}
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Card -->
          <tr>
            <td class="card">
              <!-- Hero -->
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="px py">
                    <span class="badge">Booking Update</span>
                    <h1 class="h1" style="margin-top:10px;">Hello {{ $user->name }},</h1>
                    <p class="p muted">There’s an important update to your health package booking.</p>
                  </td>
                </tr>

                <!-- Summary -->
                <tr>
                  <td class="px">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="kvs">
                      <tr>
                        <td class="k">Package</td>
                        <td class="v"><strong>{{ $package->name }}</strong></td>
                      </tr>
                      <tr>
                        <td class="k">Status</td>
                        <td class="v">Updated by administrator</td>
                      </tr>
                      <tr>
                        <td class="k">Notice</td>
                        <td class="v">{{ $cancellationReason }}</td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- CTA -->
                <tr>
                  <td class="px py" align="left" style="padding-top:8px;">
                    <!--[if mso]>
                      <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="{{ $actionUrl ?? '#' }}" arcsize="12%" strokecolor="none" fillcolor="#3b82f6" style="height:44px;v-text-anchor:middle;width:200px;">
                        <w:anchorlock/>
                        <center style="color:#ffffff;font-family:Segoe UI, Arial, sans-serif;font-size:15px;font-weight:700;">View Booking</center>
                      </v:roundrect>
                    <![endif]-->
                    <!--[if !mso]><!-->
                      <a href="{{ $actionUrl ?? '#' }}" class="btn">View Booking</a>
                    <!--<![endif]-->
                  </td>
                </tr>

                <!-- Help text -->
                <tr>
                  <td class="px" style="padding-bottom:24px;">
                    <p class="p muted" style="margin-top:8px;">
                      If you have any questions, simply reply to this email or contact our support team.
                    </p>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td class="mx" style="padding-top:16px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="card">
                <tr>
                  <td class="px py foot" align="center">
                    © {{ date('Y') }} Hospital Management System • All rights reserved<br>
                    <span class="muted">123 Healthcare Ave, City • <a href="#" style="color:inherit;text-decoration:underline;">Privacy</a></span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
