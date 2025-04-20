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

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

$resep->id = $id;
$resep->readOne();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resep->nama = $_POST['nama'];
    $resep->instruksi = $_POST['instruksi'];
    $resep->waktu_persiapan = $_POST['waktu_persiapan'];
    $resep->kategori_id = $_POST['kategori_id'];

    if ($resep->update()) {
        $query = "DELETE FROM resep_bahan WHERE resep_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if (isset($_POST['all_bahan_id']) && isset($_POST['all_jumlah'])) {
            $all_bahan_id = $_POST['all_bahan_id'];
            $all_jumlah = $_POST['all_jumlah'];

            for ($i = 0; $i < count($all_bahan_id); $i++) {
                if (!empty($all_bahan_id[$i]) && !empty($all_jumlah[$i])) {
                    $resep_bahan->resep_id = $id;
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
            <h2>Edit Resep</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
                <div class="form-group">
                    <label for="nama">Nama Resep</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $resep->nama; ?>" required>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $stmt_kategori = $kategori->read();
                        while ($row_kategori = $stmt_kategori->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($resep->kategori_id == $row_kategori['id']) ? "selected" : "";
                            echo "<option value='" . $row_kategori['id'] . "' {$selected}>" . $row_kategori['nama'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="waktu_persiapan">Waktu Persiapan (menit)</label>
                    <input type="number" class="form-control" id="waktu_persiapan" name="waktu_persiapan" value="<?php echo $resep->waktu_persiapan; ?>" required>
                </div>

                <div class="form-group">
                    <label for="instruksi">Instruksi</label>
                    <textarea class="form-control" id="instruksi" name="instruksi" rows="5" required><?php echo $resep->instruksi; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Bahan</label>
                    <div id="ingredients-container">
                        <?php
                        // Set the recipe ID explicitly
                        $resep_bahan->resep_id = $id;
                        
                        // Get ingredients for this recipe
                        $stmt_resep_bahan = $resep_bahan->readByRecipe();
                        
                        if ($stmt_resep_bahan->rowCount() > 0) {
                            while ($row_resep_bahan = $stmt_resep_bahan->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <select class="form-control" name="all_bahan_id[]">
                                            <option value="">Pilih Bahan</option>
                                            <?php
                                            $stmt_bahan = $bahan->read();
                                            while ($row_bahan = $stmt_bahan->fetch(PDO::FETCH_ASSOC)) {
                                                // Compare against the ID from the result explicitly
                                                $selected = ($row_resep_bahan['id'] == $row_bahan['id']) ? "selected" : "";
                                                echo "<option value='" . $row_bahan['id'] . "' {$selected}>" . $row_bahan['nama'] . " (" . $row_bahan['satuan'] . ")</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="all_jumlah[]" value="<?php echo $row_resep_bahan['jumlah']; ?>" placeholder="Jumlah">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-bahan">Hapus</button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <select class="form-control" name="all_bahan_id[]">
                                        <option value="">Pilih Bahan</option>
                                        <?php
                                        $stmt_bahan = $bahan->read();
                                        while ($row_bahan = $stmt_bahan->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row_bahan['id'] . "'>" . $row_bahan['nama'] . " (" . $row_bahan['satuan'] . ")</option>";
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
                            <?php
                        }
                        ?>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="add-bahan">Tambah Bahan Lain</button>
                </div>

                <button type="submit" class="btn btn-success">Update Resep</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
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
                        $stmt_bahan = $bahan->read();
                        while($row_bahan = $stmt_bahan->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row_bahan['id'] . "'>" . $row_bahan['nama'] . " (" . $row_bahan['satuan'] . ")</option>";
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