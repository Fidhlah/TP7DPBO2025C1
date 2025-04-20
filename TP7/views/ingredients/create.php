<?php
include_once '../../config/database.php';
include_once '../../models/Bahan.php';

$database = new Database();
$db = $database->getConnection();

$bahan = new Bahan($db);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $bahan->nama = $_POST['nama'];
    $bahan->satuan = $_POST['satuan'];
    
    if($bahan->create()) {
        header("Location: index.php");
        exit();
    }
}

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Buat Bahan Baru</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nama">Nama Bahan</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" placeholder="contoh: gram, cangkir, sendok makan" required>
                </div>
                
                <button type="submit" class="btn btn-success">Buat Bahan</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
include_once '../../includes/footer.php';
?>
