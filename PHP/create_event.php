<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Include koneksi database
include 'koneksi.php';

// Inisialisasi variabel pesan
$message = "";

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date = htmlspecialchars($_POST['date']);
    $location = htmlspecialchars($_POST['location']);
    $image = $_FILES['image'];

    // Validasi input
    if (!empty($title) && !empty($description) && !empty($date) && !empty($location) && $image['size'] > 0) {
        // Upload file gambar
        $target_dir = "../uploads/";
        $image_name = time() . "_" . basename($image["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Simpan ke database
            $query = "INSERT INTO events (title, description, date, location, image) VALUES (:title, :description, :date, :location, :image)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':image', $image_name);

            if ($stmt->execute()) {
                $message = "Berita berhasil ditambahkan!";
            } else {
                $message = "Gagal menyimpan data ke database.";
            }
        } else {
            $message = "Gagal mengupload gambar.";
        }
    } else {
        $message = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Berita</title>
    <link rel="stylesheet" href="../CSS/create_event.css">
</head>
<body>
<div class="container">
    <h1>Buat Berita/Kegiatan Baru</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="create_event.php" method="POST" enctype="multipart/form-data">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <label for="date">Tanggal:</label>
        <input type="date" id="date" name="date" required>

        <label for="location">Lokasi:</label>
        <input type="text" id="location" name="location" required>

        <label for="image">Gambar:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit">Simpan Berita</button>
    </form>

    <a href="admin_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html>
