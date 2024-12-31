<?php
session_start();

// Cek apakah user sudah login dan memiliki role 'siswa'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Include koneksi database
include 'koneksi.php';

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
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="../CSS/siswa_style.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <scripts src="../JS/index.js"></scripts>
</head>
<body>
<div class="container">
    <!-- Search Bar -->
    <div class="search-bar-container">
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Cari sesuatu...">
            <button type="submit" id="search-button">Search</button>
        </div>
        <div class="button-container">
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
    
    <!-- Section Kegiatan -->
    <div class="section-kegiatan">
        <div class="container">
            <h3>Berita Kegiatan Sekolah</h3>
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

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const newsContainer = document.getElementById('news-container');
        const noResultsMessage = document.getElementById('no-results');

        // Fungsi untuk memeriksa hasil pencarian
        function checkResults() {
            const newsCards = newsContainer.getElementsByClassName('card');
            const visibleCards = Array.from(newsCards).filter(card => card.style.display !== 'none');

            // Tampilkan pesan "Tidak ada berita yang ditemukan" jika tidak ada hasil
            noResultsMessage.style.display = visibleCards.length === 0 ? 'block' : 'none';
        }

        // Fungsi pencarian
        function searchNews() {
            const searchText = searchInput.value.toLowerCase().trim();
            const newsCards = newsContainer.getElementsByClassName('card');

            Array.from(newsCards).forEach(card => {
                const title = card.querySelector('h4').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();

                // Cek apakah teks pencarian cocok dengan judul atau deskripsi
                card.style.display = title.includes(searchText) || description.includes(searchText) ? '' : 'none';
            });

            checkResults();
        }

        // Event listener untuk tombol pencarian
        searchButton.addEventListener('click', searchNews);

        // Event listener untuk pencarian langsung saat mengetik
        searchInput.addEventListener('input', searchNews);
    });
</script>

</body>
</html>
