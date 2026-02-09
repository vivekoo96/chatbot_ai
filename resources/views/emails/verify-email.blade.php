<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 60px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #1e293b;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            margin-top: 40px;
        }

        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 60px 40px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
            text-decoration: none;
            text-transform: uppercase;
        }

        .content {
            padding: 50px 40px;
            text-align: center;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
            letter-spacing: -0.01em;
        }

        .paragraph {
            font-size: 16px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 40px;
        }

        .otp-container {
            background-color: #f1f5f9;
            border-radius: 20px;
            padding: 40px 20px;
            margin: 40px 0;
            border: 1px solid #e2e8f0;
        }

        .otp-code {
            font-size: 56px;
            font-weight: 800;
            color: #4f46e5;
            letter-spacing: 12px;
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
        }

        .otp-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            margin-top: 15px;
        }

        .footer {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
            font-size: 13px;
        }

        .brand-accent {
            color: #4f46e5;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <center>
            <table class="main">
                <tr>
                    <td class="header">
                        <a href="{{ config('app.url') }}" class="logo">Hemnix Assist</a>
                    </td>
                </tr>
                <tr>
                    <td class="content">
                        <h1 class="title">Verify Your Email</h1>
                        <p class="paragraph">
                            Welcome to <span class="brand-accent">Hemnix Assist</span>. To complete your registration
                            and start building your AI fleet, please use the following verification code:
                        </p>

                        <div class="otp-container">
                            <h2 class="otp-code">{{ $otp }}</h2>
                            <p class="otp-label">Secure Verification Code</p>
                        </div>

                        <p class="paragraph" style="font-size: 14px; margin-top: 40px;">
                            This code is valid for <strong>1 hour</strong>. If you didn't request this code, you can
                            safely ignore this email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="footer">
                        <p>&copy; {{ date('Y') }} Hemnix Assist. Precision AI for Modern Business.</p>
                        <p style="margin-top: 10px;">
                            <a href="{{ config('app.url') }}"
                                style="color: #4f46e5; text-decoration: none; font-weight: 600;">Visit Website</a>
                        </p>
                    </td>
                </tr>
            </table>
        </center>
    </div>
</body>

</html>