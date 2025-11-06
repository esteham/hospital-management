<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f8fafc;
        }

        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 9px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #1d4ed8, #a7c5f7 );
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .logo {
            height: 60px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            margin-left: 10px
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
            margin-left: 10px
        }

        .content {
            padding: 40px 30px;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #059669, #8ae6c7);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            margin: 10px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .credentials-panel {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 24px;
            margin: 25px 0;
        }

        .credentials-panel h3 {
            color: #0369a1;
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: 600;
        }

        .credential-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .credential-icon {
            width: 20px;
            margin-right: 12px;
            color: #3b82f6;
        }

        .checklist {
            margin: 25px 0;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }

        .priority-high { border-left-color: #ef4444; }
        .priority-medium { border-left-color: #f59e0b; }
        .priority-low { border-left-color: #10b981; }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }

        .feature-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .security-notice {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .security-notice h3 {
            color: #dc2626;
            margin-bottom: 12px;
        }

        .support-section {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }

        .footer {
            background: #1f2937;
            color: #9ca3af;
            text-align: center;
            padding: 30px;
            font-size: 14px;
        }

        .footer a {
            color: #60a5fa;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .features-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 20px 15px;
            }

            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img :src="https://your-hospital-logo.com/logo-white.png" alt="{{ config('app.name') }}" class="logo">
            <h1>Welcome to the Staff Team</h1>
            <p>{{ $name }}, your journey with {{ config('app.name') }} begins now</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2 style="color: #1f2937; margin-top: 15px;  margin-bottom: 10px; font-weight: 600;">
                    Your Digital Portal is Ready!
                </h2>
                <p style="color: #6b7280; margin-bottom: 25px;">
                    We're thrilled to welcome you to our platform. Your account has been configured with all the tools you need to support our hospital operations.
                </p>
                <a href="{{ $loginUrl }}" class="cta-button">
                    🚀 Launch Staff Portal
                </a>
            </div>

            <!-- Credentials -->
            <div class="credentials-panel">
                <h3>🔐 Your Access Credentials</h3>
                <div class="credential-item">
                    <span class="credential-icon">🌐</span>
                    <div>
                        <strong>Portal URL:</strong><br>
                        <a href="{{ $loginUrl }}" style="color: #3b82f6;">{{ $loginUrl }}</a>
                    </div>
                </div>
                <div class="credential-item">
                    <span class="credential-icon">📧</span>
                    <div>
                        <strong>Email:</strong><br>
                        {{ $email }}
                    </div>
                </div>
                <div class="credential-item">
                    <span class="credential-icon">🔑</span>
                    <div>
                        <strong>Temporary Password:</strong><br>
                        <code style=" color: rgb(255, 0, 0); padding: 4px 8px; border-radius: 4px; font-weight: bold;">{{ $password }}</code>
                    </div>
                </div>
            </div>

            <!-- Checklist -->
            <div class="checklist">
                <h3 style="color: #1f2937; margin-bottom: 20px; font-weight: 600;">📋 Onboarding Checklist</h3>

                <div class="checklist-item priority-high">
                    <span style="margin-right: 12px;">1️⃣</span>
                    <div>
                        <strong>Sign in & Change Password</strong><br>
                        <small style="color: #6b7280;">Secure your account immediately</small>
                    </div>
                </div>

                <div class="checklist-item priority-high">
                    <span style="margin-right: 12px;">2️⃣</span>
                    <div>
                        <strong>Complete Your Profile</strong><br>
                        <small style="color: #6b7280;">Add your details, designation, and photo</small>
                    </div>
                </div>

                <div class="checklist-item priority-medium">
                    <span style="margin-right: 12px;">3️⃣</span>
                    <div>
                        <strong>Explore Platform Features</strong><br>
                        <small style="color: #6b7280;">Familiarize yourself with all tools</small>
                    </div>
                </div>

                <div class="checklist-item priority-low">
                    <span style="margin-right: 12px;">4️⃣</span>
                    <div>
                        <strong>Connect with Team</strong><br>
                        <small style="color: #6b7280;">Get to know your colleagues</small>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <h3 style="color: #1f2937; margin-bottom: 20px; font-weight: 600;">💡 Platform Highlights</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">📊</span>
                    <strong>Dashboard Overview</strong>
                    <small style="color: #6b7280; display: block;">Monitor hospital operations</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">👥</span>
                    <strong>Staff Management</strong>
                    <small style="color: #6b7280; display: block;">Team coordination tools</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">💬</span>
                    <strong>Internal Communication</strong>
                    <small style="color: #6b7280; display: block;">Secure messaging system</small>
                </div>
                <div class="feature-item">
                    <span style="font-size: 24px; display: block; margin-bottom: 8px;">📋</span>
                    <strong>Task Management</strong>
                    <small style="color: #6b7280; display: block;">Track assignments and progress</small>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>⚠️ Security First</h3>
                <p style="color: #7f1d1d; margin-bottom: 10px;">
                    <strong>Important:</strong> This temporary password must be changed immediately after your first login. Never share your credentials with anyone.
                </p>
                <p style="color: #7f1d1d; font-size: 14px;">
                    Our support team will never ask for your password via email or phone.
                </p>
            </div>

            <!-- Support -->
            <div class="support-section">
                <h3 style="color: #166534; margin-bottom: 15px;">🆘 Need Help?</h3>
                <p style="color: #166534; margin-bottom: 15px;">
                    Our dedicated support team is here to assist you:
                </p>
                <div style="color: #166534;">
                    <strong>📞 Support:</strong> +880 XXXX-XXXXXX<br>
                    <strong>📧 Email:</strong> staff-support@{{ config('app.name') }}.com<br>
                    <strong>🕒 Hours:</strong> 8:00 AM - 10:00 PM (Everyday)
                </div>
            </div>

            <!-- Closing -->
            <div style="text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                <p style="color: #6b7280; margin-bottom: 20px;">
                    We're committed to providing you with the best tools to support our hospital operations.
                </p>
                <p style="font-weight: 600; color: #1f2937;">
                    Warm regards,<br>
                    <span style="color: #3b82f6;">Operations Team</span><br>
                    <strong>{{ config('app.name') }}</strong>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>Trouble accessing your account?</strong><br>
                Copy and paste this URL: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                This email contains sensitive information. Please ensure it remains confidential.<br>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
