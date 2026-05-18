<?php
// setup_database.php - File untuk setup database otomatis
session_start();

// Byet.host Database Configuration - SESUAIKAN DENGAN DATA ANDA
$host = "localhost";
$username = "sqlXXX_username"; // GANTI
$password = "password_anda"; // GANTI  
$dbname = "sqlXXX_dbname"; // GANTI

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL untuk membuat tabel users
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    
    echo "✅ Database setup berhasil! Tabel 'users' sudah dibuat.<br>";
    echo "✅ Sekarang hapus file ini untuk keamanan.<br>";
    echo "✅ <a href='register.php'>Klik di sini untuk daftar akun pertama</a>";
    
} catch(PDOException $e) {
    die("❌ Database setup gagal: " . $e->getMessage());
}
?>