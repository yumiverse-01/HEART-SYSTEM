<html>
    <head>
        <title>HEART System - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .login-container {
                width: 100%;
                max-width: 420px;
            }

            .login-card {
                border: none;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                overflow: hidden;
                animation: slideUp 0.5s ease;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .login-header {
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                color: white;
                padding: 30px;
                text-align: center;
            }

            .login-header h2 {
                font-weight: 700;
                font-size: 28px;
                margin-bottom: 5px;
                letter-spacing: 0.5px;
            }

            .login-header p {
                margin: 0;
                opacity: 0.9;
                font-size: 14px;
            }

            .card-body {
                padding: 40px 30px;
                background: white;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-label {
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 8px;
                font-size: 14px;
                display: inline-block;
            }

            .form-control {
                border-radius: 6px;
                border: 1px solid #d1d5db;
                padding: 12px 15px;
                font-size: 14px;
                transition: all 0.3s ease;
                background-color: #f9fafb;
            }

            .form-control:focus {
                border-color: #1e3a8a;
                box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
                background-color: white;
            }

            .btn-login {
                width: 100%;
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                border: none;
                color: white;
                padding: 12px;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s ease;
                margin-top: 10px;
            }

            .btn-login:hover {
                background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
                color: white;
            }

            .alert {
                border-radius: 6px;
                border: none;
                margin-bottom: 20px;
            }

            .error-text {
                color: #dc2626;
                font-size: 13px;
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="card login-card">
                <div class="login-header">
                    <h2><i class="fas fa-heart"></i> HEART</h2>
                    <p>Health Worker Portal</p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Login Failed!</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="admin@sample.com" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <small class="text-muted">
                            Demo Credentials:<br>
                            Admin: admin@sample.com / admin123<br>
                            Health Worker: healthworker@sample.com / hw123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>