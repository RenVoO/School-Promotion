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
