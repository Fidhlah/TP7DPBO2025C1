<?php
include_once '../../config/database.php';
include_once '../../models/Resep.php';
include_once '../../models/ResepBahan.php';

$database = new Database();
$db = $database->getConnection();

$resep = new Resep($db);
$resep_bahan = new ResepBahan($db);

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');
$ori_id = $id;
$resep->id = $id;

$resep->readOne();

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2><?php echo $resep->nama; ?></h2>
            <div class="card mb-4">
                <div class="card-header">
                    Detail Resep
                </div>
                <div class="card-body">
                    <p><strong>Kategori:</strong> <?php echo $resep->kategori_nama; ?></p>
                    <p><strong>Waktu Persiapan:</strong> <?php echo $resep->waktu_persiapan; ?> menit</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    Bahan-Bahan
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php
                        $resep_bahan->resep_id = $id;
                        $stmt = $resep_bahan->readByRecipe();
                        
                        if($stmt->rowCount() > 0) {
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<li class='list-group-item'>{$jumlah} {$satuan} {$nama}</li>";
                            }
                        } else {
                            echo "<li class='list-group-item'>No ingredients found.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    Instruksi
                </div>
                <div class="card-body">
                    <p><?php echo nl2br($resep->instruksi); ?></p>
                </div>
            </div>
            
            <div class="mb-4">
                <a href="edit.php?id=<?php echo $ori_id; ?>" class="btn btn-primary">Edit</a>
                <a href="delete.php?id=<?php echo $ori_id; ?>" class="btn btn-danger" onclick="return confirm('Yakin?')">Delete</a>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<?php
include_once '../../includes/footer.php';
?>
