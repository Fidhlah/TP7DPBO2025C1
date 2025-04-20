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

// Membaca data kategori berdasarkan ID
$kategori->readOne();

// Mengecek apakah form dikirimkan menggunakan metode POST
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $kategori->nama = $_POST['nama'];
    $kategori->deskripsi = $_POST['deskripsi'];
    
    // Memperbarui data kategori di database
    if($kategori->update()) {
        // Jika berhasil, arahkan ke halaman index
        header("Location: index.php");
        exit();
    }
}

// Menyertakan header HTML
include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Judul halaman -->
            <h2>Ubah Kategori</h2>
            <!-- Form untuk mengubah kategori -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
                <div class="form-group">
                    <!-- Input untuk nama kategori -->
                    <label for="nama">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $kategori->nama; ?>" required>
                </div>
                
                <div class="form-group">
                    <!-- Input untuk deskripsi kategori -->
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo $kategori->deskripsi; ?></textarea>
                </div>
                
                <!-- Tombol untuk menyimpan perubahan -->
                <button type="submit" class="btn btn-success">Ubah Kategori</button>
                <!-- Tombol untuk membatalkan dan kembali ke halaman index -->
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
// Menyertakan footer HTML
include_once '../../includes/footer.php';
?>
