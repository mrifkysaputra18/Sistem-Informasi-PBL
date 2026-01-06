<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: white; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 30px; }
        .credentials { background: #f8f9fa; padding: 25px; border-left: 4px solid #667eea; margin: 25px 0; border-radius: 4px; }
        .credential-item { margin: 15px 0; }
        .credential-label { font-weight: bold; color: #6B7280; font-size: 13px; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .credential-value { font-family: 'Courier New', monospace; font-size: 18px; color: #1F2937; padding: 12px 15px; background: white; border-radius: 6px; display: block; border: 2px solid #e5e7eb; }
        .warning { background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 20px; margin: 25px 0; border-radius: 4px; }
        .warning ul { margin: 10px 0; padding-left: 20px; }
        .warning li { margin: 5px 0; }
        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 35px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .button:hover { opacity: 0.9; }
        .footer { text-align: center; color: #6B7280; font-size: 12px; padding: 20px; background: #f9fafb; }
        .divider { height: 1px; background: #e5e7eb; margin: 25px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 SMART PBL</h1>
            <p>Politeknik Negeri Tanah Laut</p>
        </div>
        
        <div class="content">
            <h2 style="color: #1F2937; margin-top: 0;">Selamat Datang, {{ $user->name }}!</h2>
            
            <p style="font-size: 15px; color: #4B5563;">
                Akun Anda di sistem <strong>SMART PBL</strong> telah berhasil dibuat. Anda sekarang dapat mengakses sistem menggunakan kredensial di bawah ini.
            </p>
            
            <div class="credentials">
                <h3 style="margin-top: 0; color: #374151;">📧 Kredensial Login Anda</h3>
                
                <div class="credential-item">
                    <span class="credential-label">Email</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Password</span>
                    <span class="credential-value">{{ $plainPassword }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Role</span>
                    <span class="credential-value">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
            
            <div class="warning">
                <strong style="font-size: 16px;">⚠️ Penting - Harap Dibaca!</strong>
                <ul style="font-size: 14px;">
                    <li><strong>Simpan password ini dengan aman</strong> - Anda akan membutuhkannya untuk login</li>
                    <li><strong>Jangan bagikan</strong> password Anda kepada siapa pun</li>
                    <li>Anda dapat login menggunakan <strong>Google SSO</strong> atau <strong>Email & Password</strong></li>
                    <li>Untuk reset password, gunakan fitur <strong>"Lupa Password"</strong> dengan verifikasi SSO</li>
                </ul>
            </div>
            
            <div class="divider"></div>
            
            <p style="text-align: center;">
                <a href="{{ route('login') }}" class="button">
                    🚀 Login Sekarang
                </a>
            </p>
            
            <p style="font-size: 13px; color: #6B7280; text-align: center; margin-top: 20px;">
                Jika Anda mengalami kesulitan, silakan hubungi administrator sistem.
            </p>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;">&copy; {{ date('Y') }} SMART PBL - Politeknik Negeri Tanah Laut</p>
            <p style="margin: 5px 0;">Email ini dikirim secara otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>
