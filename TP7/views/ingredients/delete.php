<?php
include_once '../../config/database.php';
include_once '../../models/Bahan.php';

$database = new Database();
$db = $database->getConnection();

$bahan = new Bahan($db);

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID tidak ditemukan.');

$bahan->id = $id;

if($bahan->delete()) {
    header("Location: index.php");
} else {
    include_once '../../includes/header.php';
    ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            <p>Tidak dapat menghapus bahan. Bahan ini sedang digunakan oleh satu atau lebih resep.</p>
            <a href="index.php" class="btn btn-primary">Kembali ke Daftar Bahan</a>
        </div>
    </div>
    <?php
    include_once '../../includes/footer.php';
}
?>
