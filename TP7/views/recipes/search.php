<?php
include_once '../../config/database.php';
include_once '../../models/Resep.php';

$database = new Database();
$db = $database->getConnection();

$resep = new Resep($db);

$search = isset($_GET['search']) ? $_GET['search'] : '';

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Hasil Pencarian</h2>
            <p>Search keyword: "<?php echo htmlspecialchars($search); ?>"</p>
        </div>
        <div class="col-md-6 text-right">
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Waktu Persiapan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $resep->search($search);
                    
                    if($stmt->rowCount() > 0) {
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            
                            echo "<tr>";
                                echo "<td>{$nama}</td>";
                                echo "<td>{$kategori_nama}</td>";
                                echo "<td>{$waktu_persiapan} min</td>";
                                echo "<td>";
                                    echo "<a href='view.php?id={$id}' class='btn btn-info btn-sm mr-1'>View</a>";
                                    echo "<a href='edit.php?id={$id}' class='btn btn-primary btn-sm mr-1'>Edit</a>";
                                    echo "<a href='delete.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                                echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No recipes found matching your search.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once '../../includes/footer.php';
?>
