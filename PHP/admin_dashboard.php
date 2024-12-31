<?php
session_start();

// Periksa apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$username = $_SESSION['username'];

// Include koneksi database
include 'koneksi.php';

// Proses hapus berita
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Hapus gambar dari folder uploads
    $query = "SELECT image FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $delete_id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event && file_exists("../uploads/" . $event['image'])) {
        unlink("../uploads/" . $event['image']);
    }

    // Hapus berita dari database
    $query = "DELETE FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $delete_id);

    if ($stmt->execute()) {
        header('Location: admin_dashboard.php');
        exit();
    } else {
        echo "Gagal menghapus berita.";
    }
}

// Query untuk mengambil data event
$query = "SELECT * FROM events ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../CSS/siswa_style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="../JS/index.js"></script>
</head>
<body>
<div class="container">
    <!-- Search Bar, Tombol Logout, dan Buat Berita -->
    <div class="search-bar-container">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Cari sesuatu...">
            <button type="submit" id="search-button">Search</button>
        </div>
        <div class="button-container">
            <a href="create_event.php" class="create-event-button"><i class='bx bx-plus-circle'></i></a>
            <a href="logout.php" class="logout-button"><i class='bx bx-log-out-circle'></i></a>
        </div>
    </div>

    <!-- Header Image -->
    <div class="image-container">
        <img src="../asset/logo.png" alt="Banner Image">
        <div class="overlay">
            <h1>Selamat Datang Di Website</h1>
            <p>SMKN 1 Padaherang</p>
        </div>
    </div>

    <div class="section-kegiatan">
    <div class="container">
        <h3>Daftar Berita Kegiatan</h3>
        <?php if (count($events) > 0): ?>
            <div class="grid-container" id="news-container">
            <p id="no-results" class="no-results" style="display: none;">Tidak ada berita yang ditemukan.</p>
                <?php foreach ($events as $row): ?>
                    <div class="card">
                        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Gambar Kegiatan">
                        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                        <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <div class="card-actions">
                            <a href="event_detail.php?id=<?php echo $row['id']; ?>">Detail</a>
                            <a href="edit_event.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="admin_dashboard.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada kegiatan saat ini.</p>
        <?php endif; ?>
    </div>
</div>

</div>
</body>
</html>
