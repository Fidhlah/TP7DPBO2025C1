<?php
class Kategori {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "Kategori";

    // Properti objek
    public $id;
    public $nama;
    public $deskripsi;

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menambahkan data kategori baru ke database
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, deskripsi=:deskripsi";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input untuk mencegah serangan XSS
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":deskripsi", $this->deskripsi);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk membaca semua data kategori dari database
    public function read() {
        $query = "SELECT id, nama, deskripsi FROM " . $this->table_name . " ORDER BY nama";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Fungsi untuk membaca satu data kategori berdasarkan ID
    public function readOne() {
        $query = "SELECT id, nama, deskripsi FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nama = $row['nama'];
            $this->deskripsi = $row['deskripsi'];
            return true;
        }

        return false;
    }

    // Fungsi untuk memperbarui data kategori berdasarkan ID
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, deskripsi=:deskripsi WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        $stmt->bindParam(":id", $this->id);

        // Eksekusi query
        if($stmt->execute()) { 
            return true;
        }

        return false;
    }

    // Fungsi untuk menghapus data kategori berdasarkan ID
    public function delete() {
        // Mengecek apakah kategori sedang digunakan di tabel `resep`
        $query = "SELECT COUNT(*) as count FROM resep WHERE kategori_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Jika kategori sedang digunakan, penghapusan tidak diizinkan
        if($row['count'] > 0) {
            return false;
        }
        
        // Menghapus data kategori dari tabel
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
