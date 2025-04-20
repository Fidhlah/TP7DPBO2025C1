

<?php
// Menyertakan file header untuk tampilan HTML
include_once 'includes/header.php';
//Saya Muhammad Hafidh Fadhilah dengan NIM 2305672 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.
?>
<div class="container mt-5">
    <!-- Judul utama halaman -->
    <h1 class="mb-4">Recipe Management System</h1>
    <p class="lead">Simpan dan atur resepmu!</p>
    
    <div class="row mt-4">
        <!-- Kartu untuk menu Resep -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <!-- Judul kartu -->
                    <h5 class="card-title">Resep</h5>
                    <!-- Deskripsi kartu -->
                    <p class="card-text">Koleksi resep.</p>
                    <!-- Tombol untuk menuju halaman daftar resep -->
                    <a href="views/recipes/index.php" class="btn btn-primary">Lihat Resep</a>
                </div>
            </div>
        </div>
        
        <!-- Kartu untuk menu Bahan -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <!-- Judul kartu -->
                    <h5 class="card-title">Bahan</h5>
                    <!-- Deskripsi kartu -->
                    <p class="card-text">Atur bahan bahan yang dibutuhkan resepmu.</p>
                    <!-- Tombol untuk menuju halaman daftar bahan -->
                    <a href="views/ingredients/index.php" class="btn btn-primary">Lihat Bahan</a>
                </div>
            </div>
        </div>
        
        <!-- Kartu untuk menu Kategori -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <!-- Judul kartu -->
                    <h5 class="card-title">Kategori</h5>
                    <!-- Deskripsi kartu -->
                    <p class="card-text">Atur resep berdasarkan kategori.</p>
                    <!-- Tombol untuk menuju halaman daftar kategori -->
                    <a href="views/categories/index.php" class="btn btn-primary">Lihat Kategori</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Menyertakan file footer untuk tampilan HTML
include_once 'includes/footer.php';
?>
