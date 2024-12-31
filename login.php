<?php
$title = "Login";
$action = "./PHP/auth_process.php";
$buttonText = "Login";
$toggleText = "Belum punya akun? <a href='./PHP/register.php'>Daftar</a>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="./CSS/Form.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $title; ?></h2>
        <form method="POST" action="<?php echo $action; ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

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
