<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    try {
        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'Username sudah digunakan. Silakan gunakan username lain.';
            header('Location: register.php');
            exit();
        }

        // Validate password and confirm password
        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
            header('Location: register.php');
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        $_SESSION['success'] = 'Registrasi berhasil! Silakan login.';
        header('Location: ../login.php');
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = 'Terjadi kesalahan pada server: ' . $e->getMessage();
        header('Location: register.php');
        exit();
    }
} else {
    // If accessed without POST request, redirect to register
    header('Location: register.php');
    exit();
}
?>
