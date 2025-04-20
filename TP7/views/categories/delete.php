<?php
// Mengimpor file konfigurasi database dan model Kategori
include_once '../../config/database.php';
include_once '../../models/Kategori.php';

// Membuat koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek Kategori
$kategori = new Kategori($db);

// Mendapatkan ID kategori dari parameter URL
$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

// Menetapkan ID kategori ke properti objek
$kategori->id = $id;

// Mengecek apakah kategori berhasil dihapus
if($kategori->delete()) {
    // Jika berhasil, arahkan ke halaman index
    header("Location: index.php");
} else {
    // Jika gagal, tampilkan pesan kesalahan
    include_once '../../includes/header.php';
    ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            <!-- Pesan kesalahan jika kategori tidak dapat dihapus -->
            <p>Tidak dapat menghapus kategori. Kategori ini sedang digunakan oleh satu atau lebih resep.</p>
            <!-- Tombol untuk kembali ke daftar kategori -->
            <a href="index.php" class="btn btn-primary">Kembali ke Daftar Kategori</a>
        </div>
    </div>
    <?php
    // Menyertakan footer HTML
    include_once '../../includes/footer.php';
}
?>
