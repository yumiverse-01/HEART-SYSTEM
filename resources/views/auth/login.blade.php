<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEART System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: #f3f4f6;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath d='M10 50 L30 50 L35 40 L45 60 L50 50 L90 50' stroke='%231e3a8a' stroke-width='1' fill='none' opacity='0.1'/%3E%3C/svg%3E");
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Prevents card touching screen edges */
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            background: white;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header h2 {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 4px;
        }

        /* Responsive padding for card body */
        .card-body { padding: 1.5rem; }
        @media (min-width: 576px) { .card-body { padding: 2.5rem 2rem; } }

        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Larger touch targets for mobile (48px height) */
        .form-control {
            height: 48px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }

        .password-container { position: relative; }
        .password-container input { padding-right: 45px; }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            padding: 10px;
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 0.5rem;
            transition: opacity 0.2s;
        }
        
        .btn-login:active { opacity: 0.8; transform: scale(0.98); }

        .demo-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="login-header">
                <h2><i class="fas fa-heart-pulse"></i> HEART</h2>
                <p class="mb-0 opacity-75">Barangay Portal</p>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger small py-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf                        
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login">
                        Login <i class="fas fa-arrow-right ms-2 small"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordField = document.getElementById("password");
        const toggleBtn = document.getElementById("togglePassword");

        toggleBtn.addEventListener("click", function () {
            const isPassword = passwordField.type === "password";
            passwordField.type = isPassword ? "text" : "password";
            
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>