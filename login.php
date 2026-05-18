<?php
require_once 'config.php';

$error = '';
$success = '';

// Check for success message from register
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        $sql = "SELECT id, username, password, full_name FROM users WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['logged_in'] = true;
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Username/email atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portfolio Abby</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #000000;
            --secondary-color: #f97316;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: auto;
            padding: 20px 0;
        }
        
        /* Background Animation - Simplified */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.7;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 40px;
            height: 40px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 40%;
            left: 5%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
            transition: all 0.3s ease;
            margin: 0 auto;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #333 100%);
            color: white;
            text-align: center;
            padding: 1.5rem 1rem;
            border-bottom: none;
            position: relative;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.8rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .card-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
        }
        
        .card-subtitle {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 1.8rem 1.5rem;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 1.2rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.7rem 1rem 0.7rem 2.8rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            height: 48px;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }
        
        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: all 0.3s ease;
            z-index: 3;
        }
        
        .form-control:focus + .form-icon {
            color: var(--secondary-color);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #ea580c 100%);
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            height: 48px;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(249, 115, 22, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 1rem 1.2rem;
            margin-bottom: 1.2rem;
            font-size: 0.95rem;
            animation: slideIn 0.5s ease-out;
            display: flex;
            align-items: center;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border-left: 4px solid #dc2626;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
        }
        
        .success-icon {
            font-size: 1.5rem;
            margin-right: 0.8rem;
            animation: bounce 0.6s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }
        
        .additional-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.2rem 0;
            font-size: 0.9rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            cursor: pointer;
        }
        
        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--secondary-color);
        }
        
        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .forgot-password:hover {
            color: #ea580c;
            text-decoration: underline;
        }
        
        .social-login {
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .social-divider {
            position: relative;
            text-align: center;
            margin: 1.2rem 0;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .social-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }
        
        .social-divider span {
            background: white;
            padding: 0 0.8rem;
            position: relative;
        }
        
        .social-buttons {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
        }
        
        .social-btn {
            flex: 1;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.6rem;
            background: white;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            height: 44px;
        }
        
        .social-btn:hover {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .register-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        /* Responsive adjustments for very small screens */
        @media (max-width: 480px) {
            body {
                padding: 15px 10px;
                align-items: flex-start;
            }
            
            .container {
                max-width: 100%;
            }
            
            .card {
                border-radius: 12px;
            }
            
            .card-header {
                padding: 1.2rem 1rem;
            }
            
            .card-body {
                padding: 1.5rem 1.2rem;
            }
            
            .logo {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
            
            .card-title {
                font-size: 1.3rem;
            }
            
            .social-buttons {
                flex-direction: column;
            }
            
            .additional-options {
                flex-direction: column;
                gap: 0.8rem;
                align-items: flex-start;
            }
        }
        
        @media (max-height: 700px) {
            body {
                align-items: flex-start;
                padding-top: 30px;
                padding-bottom: 30px;
            }
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="logo">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h1 class="card-title">Selamat Datang</h1>
                <p class="card-subtitle">Login ke akun portfolio Anda</p>
            </div>
            <div class="card-body">
                <!-- Success Message dari Register -->
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <div>
                            <strong>Berhasil!</strong><br>
                            <?php echo $success; ?>
                        </div>
                    </div>
                    
                    <script>
                        // Auto scroll ke success message
                        document.addEventListener('DOMContentLoaded', function() {
                            const successAlert = document.querySelector('.alert-success');
                            if (successAlert) {
                                successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    </script>
                <?php endif; ?>
                
                <!-- Error Message dari Login -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                    
                    <script>
                        // Auto scroll ke error message
                        document.addEventListener('DOMContentLoaded', function() {
                            const errorAlert = document.querySelector('.alert-danger');
                            if (errorAlert) {
                                errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });
                    </script>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <i class="bi bi-person form-icon"></i>
                        <input type="text" class="form-control" name="username" placeholder="Username atau Email" required 
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <i class="bi bi-lock form-icon"></i>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    
                    <div class="additional-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Ingat saya
                        </label>
                        <a href="#" class="forgot-password">Lupa password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Login
                    </button>
                </form>
                
                <div class="social-login">
                    <div class="social-divider">
                        <span>Atau login dengan</span>
                    </div>
                    <div class="social-buttons">
                        <button class="social-btn">
                            <i class="bi bi-google"></i>
                            Google
                        </button>
                        <button class="social-btn">
                            <i class="bi bi-github"></i>
                            GitHub
                        </button>
                    </div>
                </div>
                
                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
        
        // Add loading animation on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-arrow-repeat spinner me-2"></i>Memproses...';
            btn.disabled = true;
            
            // Biarkan form submit normal
        });
        
        // Auto remove alerts setelah 5 detik
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>