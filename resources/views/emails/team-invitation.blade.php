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

        .credentials-container {
            background-color: #f1f5f9;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .credential-row {
            margin-bottom: 15px;
        }

        .credential-label {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: block;
            margin-bottom: 5px;
        }

        .credential-value {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            word-break: break-all;
        }

        .button {
            display: inline-block;
            padding: 18px 36px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 16px;
            margin-top: 20px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
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
                        <h1 class="title">Join the Team</h1>
                        <p class="paragraph">
                            Hello! <span class="brand-accent">{{ $inviterName }}</span> has invited you to join their
                            workspace on <strong>{{ $businessName }}</strong>.
                        </p>

                        <p class="paragraph" style="margin-bottom: 20px;">Use the credentials below to access your
                            dashboard:</p>

                        <div class="credentials-container">
                            <div class="credential-row">
                                <span class="credential-label">Email Address</span>
                                <span class="credential-value">{{ $email }}</span>
                            </div>
                            <div class="credential-row" style="margin-bottom: 0;">
                                <span class="credential-label">Temporary Password</span>
                                <span class="credential-value">{{ $password }}</span>
                            </div>
                        </div>

                        <a href="{{ url('/login') }}" class="button">Access Dashboard</a>

                        <p class="paragraph" style="font-size: 14px; margin-top: 40px; color: #94a3b8;">
                            For security, please change your password immediately after logging in.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="footer">
                        <p>&copy; {{ date('Y') }} Hemnix Assist. Precision AI for Modern Business.</p>
                    </td>
                </tr>
            </table>
        </center>
    </div>
</body>

</html>