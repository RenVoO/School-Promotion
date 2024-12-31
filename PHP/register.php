<?php
session_start();
$title = "Register";
$action = "register_process.php";
$buttonText = "Daftar";
$toggleText = "Sudah punya akun? <a href='../login.php'>Login</a>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../CSS/Form.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $title; ?></h2>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Tampilkan pesan sukses jika ada -->
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST" action="<?php echo $action; ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="siswa">Siswa</option>
            </select>

            <button type="submit"><?php echo $buttonText; ?></button>
        </form>

        <div class="toggle-link">
            <p><?php echo $toggleText; ?></p>
        </div>
    </div>
</body>
</html>
