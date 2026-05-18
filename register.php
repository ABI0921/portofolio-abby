<?php
require_once 'config.php';

$show_success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);

    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "Semua field harus diisi!";
    } else {
        try {
            $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$username, $email]);
            
            if ($check_stmt->rowCount() > 0) {
                $error = "Username atau email sudah terdaftar!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                
                if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
                    $show_success = true;
                    // Clear form fields
                    $_POST = array();
                } else {
                    $error = "Terjadi kesalahan saat registrasi!";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Portfolio Abby</title>
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
            width: 50px;
            height: 50px;
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 70px;
            height: 70px;
            top: 65%;
            left: 85%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 80%;
            left: 15%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        
        .container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
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
            margin-bottom: 1rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.7rem 1rem 0.7rem 2.8rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            height: 46px;
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
        
        .btn-register {
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
        
        .btn-register:hover {
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
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border-left: 4px solid #dc2626;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
            border-left: 4px solid #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
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
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .feature-list {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 10px;
            padding: 1.2rem;
            margin-top: 1.5rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            padding: 0.4rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .feature-item:last-child {
            margin-bottom: 0;
        }
        
        .feature-item:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        .feature-icon {
            width: 32px;
            height: 32px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 0.8rem;
            flex-shrink: 0;
            font-size: 0.9rem;
        }
        
        .feature-text {
            color: #475569;
        }
        
        /* Responsive adjustments */
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
                    <i class="bi bi-person-plus"></i>
                </div>
                <h1 class="card-title">Daftar Akun Baru</h1>
                <p class="card-subtitle">Bergabunglah dengan portfolio community</p>
            </div>
            <div class="card-body">
                <!-- Alert Error -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Alert Success -->
                <?php if ($show_success): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <div>
                            <strong>Registrasi Berhasil!</strong><br>
                            Akun Anda telah berhasil dibuat. Silakan <a href="login.php" style="color: #065f46; font-weight: 600;">login</a> untuk melanjutkan.
                        </div>
                    </div>
                    
                    <!-- Reset form fields dengan JavaScript -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Clear all form fields
                            const form = document.querySelector('form');
                            form.reset();
                            
                            // Scroll to success message
                            const successAlert = document.querySelector('.alert-success');
                            if (successAlert) {
                                successAlert.scrollIntoView({ behavior: 'smooth' });
                            }
                        });
                    </script>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <i class="bi bi-person form-icon"></i>
                        <input type="text" class="form-control" name="full_name" placeholder="Nama Lengkap" required 
                               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <i class="bi bi-at form-icon"></i>
                        <input type="text" class="form-control" name="username" placeholder="Username" required
                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <i class="bi bi-envelope form-icon"></i>
                        <input type="email" class="form-control" name="email" placeholder="Alamat Email" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <i class="bi bi-lock form-icon"></i>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus me-2"></i>
                        Daftar Sekarang
                    </button>
                </form>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Portfolio Personal</strong> - Tampilkan karya terbaikmu
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Keamanan Terjamin</strong> - Data Anda terlindungi
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <div class="feature-text">
                            <strong>Akses Cepat</strong> - Loading super cepat
                        </div>
                    </div>
                </div>
                
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Login di sini</a>
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
            const btn = this.querySelector('.btn-register');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-arrow-repeat spinner me-2"></i>Membuat Akun...';
            btn.disabled = true;
            
            // Biarkan form submit normal
        });
        
        // Auto scroll to alert jika ada
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>