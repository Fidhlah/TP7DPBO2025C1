<?php
class Resep {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "resep";

    // Properti objek
    public $id;
    public $nama;
    public $instruksi;
    public $waktu_persiapan;
    public $kategori_id;
    public $dibuat_pada;
    public $kategori_nama;

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menambahkan data resep baru ke database
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, instruksi=:instruksi, waktu_persiapan=:waktu_persiapan, kategori_id=:kategori_id, dibuat_pada=:dibuat_pada";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data input untuk mencegah serangan XSS
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->instruksi = htmlspecialchars(strip_tags($this->instruksi));
        $this->waktu_persiapan = htmlspecialchars(strip_tags($this->waktu_persiapan));
        $this->kategori_id = htmlspecialchars(strip_tags($this->kategori_id));
        $this->dibuat_pada = date('Y-m-d H:i:s'); // Menyimpan waktu saat ini

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":instruksi", $this->instruksi);
        $stmt->bindParam(":waktu_persiapan", $this->waktu_persiapan);
        $stmt->bindParam(":kategori_id", $this->kategori_id);
        $stmt->bindParam(":dibuat_pada", $this->dibuat_pada);

        // Eksekusi query
        if($stmt->execute()) {
            return $this->conn->lastInsertId(); // Mengembalikan ID terakhir yang dimasukkan
        }

        return false;
    }

    // Fungsi untuk membaca semua data resep dari database
    public function read() {
        $query = "SELECT c.nama as kategori_nama, r.id, r.nama, r.instruksi, r.waktu_persiapan, r.kategori_id, r.dibuat_pada FROM " . $this->table_name . " r LEFT JOIN kategori c ON r.kategori_id = c.id ORDER BY r.dibuat_pada DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Fungsi untuk membaca satu data resep berdasarkan ID
    public function readOne() {
        $query = "SELECT c.nama as kategori_nama, r.id, r.nama, r.instruksi, r.waktu_persiapan, r.kategori_id, r.dibuat_pada FROM " . $this->table_name . " r LEFT JOIN kategori c ON r.kategori_id = c.id WHERE r.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nama = $row['nama'];
            $this->instruksi = $row['instruksi'];
            $this->waktu_persiapan = $row['waktu_persiapan'];
            $this->kategori_id = $row['kategori_id'];
            $this->dibuat_pada = $row['dibuat_pada'];
            $this->kategori_nama = $row['kategori_nama'];
            return true;
        }

        return false;
    }

    // Fungsi untuk memperbarui data resep berdasarkan ID
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama=:nama, instruksi=:instruksi, waktu_persiapan=:waktu_persiapan, kategori_id=:kategori_id WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Membersihkan data input
        $this->nama = htmlspecialchars(strip_tags($this->nama));
        $this->instruksi = htmlspecialchars(strip_tags($this->instruksi));
        $this->waktu_persiapan = htmlspecialchars(strip_tags($this->waktu_persiapan));
        $this->kategori_id = htmlspecialchars(strip_tags($this->kategori_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Mengikat parameter
        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":instruksi", $this->instruksi);
        $stmt->bindParam(":waktu_persiapan", $this->waktu_persiapan);
        $stmt->bindParam(":kategori_id", $this->kategori_id);
        $stmt->bindParam(":id", $this->id);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk menghapus data resep berdasarkan ID
    public function delete() {
        // Menghapus data terkait di tabel `resep_bahan`
        $query = "DELETE FROM resep_bahan WHERE resep_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        // Menghapus data resep dari tabel
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        // Eksekusi query
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk mencari data resep berdasarkan kata kunci
    public function search($keywords) {
        $query = "SELECT c.nama as kategori_nama, r.id, r.nama, r.instruksi, r.waktu_persiapan, r.kategori_id, r.dibuat_pada FROM " . $this->table_name . " r LEFT JOIN kategori c ON r.kategori_id = c.id WHERE r.nama LIKE ? OR r.instruksi LIKE ? ORDER BY r.dibuat_pada DESC";

        $stmt = $this->conn->prepare($query);

        // Membersihkan kata kunci
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // Mengikat parameter
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);

        $stmt->execute();

        return $stmt;
    }
}
?>
