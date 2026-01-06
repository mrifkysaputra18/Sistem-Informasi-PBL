<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-container {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
        }
        .otp-box {
            background: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .otp-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .expires {
            color: #dc2626;
            font-size: 14px;
            margin-top: 10px;
        }
        .content {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.8;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #9ca3af;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 Reset Password SMART PBL</h1>
        </div>

        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        </div>

        <div class="otp-box">
            <div class="otp-label">Kode OTP Anda:</div>
            <div class="otp-code">{{ $otpCode }}</div>
            <div class="expires">⏱️ Kode berlaku selama {{ $expiresIn }}</div>
        </div>

        <div class="content">
            <p>Masukkan kode OTP di atas pada halaman verifikasi untuk melanjutkan proses reset password.</p>
        </div>

        <div class="warning">
            <p><strong>⚠️ Perhatian:</strong></p>
            <p>• Jangan bagikan kode OTP ini kepada siapapun</p>
            <p>• Jika Anda tidak meminta reset password, abaikan email ini</p>
            <p>• Kode OTP hanya dapat digunakan sekali</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh SMART PBL System</p>
            <p>Politeknik Negeri Tanah Laut</p>
        </div>
    </div>
</body>
</html>
