<?php
session_start();
include 'koneksi.php';

// Cek apakah ada session user dan periksa ID event dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data event
    $query = "SELECT * FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Event tidak ditemukan.";
        exit();
    }
} else {
    // Jika tidak ada ID event, arahkan ke halaman dashboard admin sebagai default
    header('Location: admin_dashboard.php');
    exit();
}

// Tentukan halaman kembali berdasarkan role user
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        $dashboardLink = 'admin_dashboard.php';
    } elseif ($_SESSION['role'] === 'siswa') {
        $dashboardLink = 'siswa_dashboard.php';
    } else {
        // Jika role tidak dikenali, arahkan ke halaman login
        header('Location: login.php');
        exit();
    }
} else {
    // Jika session tidak ada, arahkan ke login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Berita</title>
    <link rel="stylesheet" href="../CSS/event_detail.css">
</head>
<body>
<div class="container">
    <h1><?php echo htmlspecialchars($event['title']); ?></h1>
    <img src="../uploads/<?php echo htmlspecialchars($event['image']); ?>" alt="Gambar Kegiatan" width="1000">
    <p><?php echo htmlspecialchars($event['description']); ?></p>
    <p><strong>Tanggal:</strong> <?php echo $event['date']; ?></p>
    <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
    <br>
    <!-- Tombol Kembali ke Dashboard berdasarkan role -->
    <a href="<?php echo $dashboardLink; ?>">Kembali ke Dashboard</a>
</div>
</body>
</html>
