<?php
session_start();
require 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    try {
     
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND role = :role");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } elseif ($user['role'] === 'siswa') {
                header('Location: siswa_dashboard.php');
            }
            exit();
        } else {
            
            $_SESSION['error'] = 'Username atau password salah.';
            header('Location: ../login.php');
            exit();
        }
    } catch (PDOException $e) {
        
        $_SESSION['error'] = 'Terjadi kesalahan pada server. Silakan coba lagi.';
        header('Location: ../login.php');
        exit();
    }
} else {
    
    header('Location: ../login.php');
    exit();
}
?>
