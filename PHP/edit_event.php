<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Include koneksi database
include 'koneksi.php';

// Inisialisasi pesan
$message = "";

// Ambil ID event dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data event
    $query = "SELECT * FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        $message = "Berita tidak ditemukan.";
    }
} else {
    header('Location: admin_dashboard.php');
    exit();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date = htmlspecialchars($_POST['date']);
    $location = htmlspecialchars($_POST['location']);
    $image = $_FILES['image'];

    // Validasi input
    if (!empty($title) && !empty($description) && !empty($date) && !empty($location)) {
        $image_name = $event['image']; // Default gambar sebelumnya

        // Proses upload gambar baru jika ada
        if ($image['size'] > 0) {
            $target_dir = "../uploads/";
            $image_name = time() . "_" . basename($image["name"]);
            $target_file = $target_dir . $image_name;

            // Hapus gambar lama jika ada gambar baru
            if (file_exists("../uploads/" . $event['image'])) {
                unlink("../uploads/" . $event['image']);
            }

            if (!move_uploaded_file($image["tmp_name"], $target_file)) {
                $message = "Gagal mengupload gambar.";
            }
        }

        // Update data di database
        $query = "UPDATE events SET title = :title, description = :description, date = :date, location = :location, image = :image WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':image', $image_name);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $message = "Berita berhasil diperbarui!";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $message = "Gagal memperbarui berita.";
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
    <title>Edit Berita</title>
    <link rel="stylesheet" href="../CSS/create_event.css">
</head>
<body>
<div class="container">
    <h1>Edit Berita/Kegiatan</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="edit_event.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>

        <label for="description">Deskripsi:</label>
        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>

        <label for="date">Tanggal:</label>
        <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>

        <label for="location">Lokasi:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>

        <label for="image">Gambar Baru (Opsional):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <p>Gambar saat ini:</p>
        <img src="../uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Gambar Kegiatan" width="150">
        <br><br>

        <button type="submit">Perbarui Berita</button>
    </form>

    <a href="admin_dashboard.php">Kembali ke Dashboard</a>
</div>
</body>
</html>
