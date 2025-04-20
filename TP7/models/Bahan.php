<?php
class Bahan {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "bahan";

    // Properti objek
    public $id;
    public $nama;
    public $satuan;
    public $dibuat_pada;

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menambahkan data bahan baru ke database
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, satuan=:satuan, dibuat_pada=:dibuat_pada";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input untuk mencegah serangan XSS
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->satuan = htmlspecialchars(strip_tags($this->satuan));
        $this->dibuat_pada = date('Y-m-d H:i:s'); // Menyimpan waktu saat ini

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":satuan", $this->satuan);
        $stmt->bindParam(":dibuat_pada", $this->dibuat_pada);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk membaca semua data bahan dari database
    public function read() {
        $query = "SELECT id, nama, satuan, dibuat_pada FROM " . $this->table_name . " ORDER BY nama";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Fungsi untuk membaca satu data bahan berdasarkan ID
    public function readOne() {
        $query = "SELECT id, nama, satuan, dibuat_pada FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nama = $row['nama'];
            $this->satuan = $row['satuan'];
            $this->dibuat_pada = $row['dibuat_pada'];
            return true;
        }

        return false;
    }

    // Fungsi untuk memperbarui data bahan berdasarkan ID
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, satuan=:satuan WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->satuan = htmlspecialchars(strip_tags($this->satuan));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":satuan", $this->satuan);
        $stmt->bindParam(":id", $this->id);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk menghapus data bahan berdasarkan ID
    public function delete() {
        // Mengecek apakah bahan sedang digunakan di tabel `resep_bahan`
        $query = "SELECT COUNT(*) as count FROM resep_bahan WHERE bahan_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Jika bahan sedang digunakan, penghapusan tidak diizinkan
        if($row['count'] > 0) {
            return false; 
        }
        
        // Menghapus data bahan dari tabel
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
