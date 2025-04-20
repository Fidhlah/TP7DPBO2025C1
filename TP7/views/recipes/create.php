<?php
include_once '../../config/database.php';
include_once '../../models/Resep.php';
include_once '../../models/Kategori.php';
include_once '../../models/Bahan.php';
include_once '../../models/ResepBahan.php';

$database = new Database();
$db = $database->getConnection();

$resep = new Resep($db);
$kategori = new Kategori($db);
$bahan = new Bahan($db);
$resep_bahan = new ResepBahan($db);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $resep->nama = $_POST['nama'];
    $resep->instruksi = $_POST['instruksi'];
    $resep->waktu_persiapan = $_POST['waktu_persiapan'];
    $resep->kategori_id = $_POST['kategori_id'];
    
    $resep_id = $resep->create();
    
    if($resep_id) {
        if(!empty($_POST['all_bahan_id']) && !empty($_POST['all_jumlah'])) {
            $all_bahan_id = $_POST['all_bahan_id'];
            $all_jumlah = $_POST['all_jumlah'];
            
            for($i = 0; $i < count($all_bahan_id); $i++) {
                if(!empty($all_bahan_id[$i]) && !empty($all_jumlah[$i])) {
                    $resep_bahan->resep_id = $resep_id;
                    $resep_bahan->bahan_id = $all_bahan_id[$i];
                    $resep_bahan->jumlah = $all_jumlah[$i];
                    $resep_bahan->create();
                }
            }
        }
        
        header("Location: index.php");
        exit();
    }
}

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Buat Resep Baru</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="nama">Nama Resep</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                
                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $stmt = $kategori->read();
                        
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<option value='{$id}'>{$nama}</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="waktu_persiapan">Waktu Persiapan (menit)</label>
                    <input type="number" class="form-control" id="waktu_persiapan" name="waktu_persiapan" required>
                </div>
                
                <div class="form-group">
                    <label for="instruksi">Instruksi</label>
                    <textarea class="form-control" id="instruksi" name="instruksi" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Bahan-Bahan</label>
                    <div id="ingredients-container">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <select class="form-control" name="all_bahan_id[]">
                                    <option value="">Pilih Bahan</option>
                                    <?php
                                    $stmt = $bahan->read();
                                    
                                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        extract($row);
                                        echo "<option value='{$id}'>{$nama} ({$satuan})</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="all_jumlah[]" placeholder="Jumlah">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="add-bahan">Tambah Bahan Lain</button>
                </div>
                
                <button type="submit" class="btn btn-success">Buat Resep</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('add-bahan').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const row = document.createElement('div');
            row.className = 'row mb-2';
            row.innerHTML = `
                <div class="col-md-6">
                    <select class="form-control" name="all_bahan_id[]">
                        <option value="">Pilih Bahan</option>
                        <?php
                        $stmt = $bahan->read();
                        
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<option value='{$id}'>{$nama} ({$satuan})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="all_jumlah[]" placeholder="Jumlah">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                </div>
            `;
            container.appendChild(row);
        });
        
        document.addEventListener('click', function(e) {
            if(e.target && e.target.classList.contains('remove-bahan')) {
                const row = e.target.closest('.row');
                if(document.querySelectorAll('#ingredients-container .row').length > 1) {
                    row.remove();
                }
            }
        });
    });
</script>

<?php
include_once '../../includes/footer.php';
?>
