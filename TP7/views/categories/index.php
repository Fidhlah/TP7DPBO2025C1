<?php
include_once '../../config/database.php';
include_once '../../models/Kategori.php';

$database = new Database();
$db = $database->getConnection();

$kategori = new Kategori($db);

include_once '../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Kategori</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="create.php" class="btn btn-success">Tambah Kategori Baru</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $kategori->read();
                    
                    if($stmt->rowCount() > 0) {
                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            
                            echo "<tr>";
                                echo "<td>{$nama}</td>";
                                echo "<td>{$deskripsi}</td>";
                                echo "<td>";
                                    echo "<a href='edit.php?id={$id}' class='btn btn-primary btn-sm mr-1'>Edit</a>";
                                    echo "<a href='delete.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin?\")'>Delete</a>";
                                echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Tidak ada kategori yang ditemukan.</td></tr>";
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
