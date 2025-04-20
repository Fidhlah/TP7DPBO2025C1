<?php
class ResepBahan {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "resep_bahan";

    // Properti objek
    public $resep_id; // ID resep
    public $bahan_id; // ID bahan
    public $jumlah;   // Jumlah bahan yang digunakan dalam resep

    // Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk menambahkan data baru ke tabel `resep_bahan`
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET resep_id=:resep_id, bahan_id=:bahan_id, jumlah=:jumlah";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input untuk mencegah serangan XSS
        $this->resep_id = htmlspecialchars(strip_tags($this->resep_id));
        $this->bahan_id = htmlspecialchars(strip_tags($this->bahan_id));
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));

        // Mengikat parameter
        $stmt->bindParam(":resep_id", $this->resep_id);
        $stmt->bindParam(":bahan_id", $this->bahan_id);
        $stmt->bindParam(":jumlah", $this->jumlah);

        // Eksekusi query dan cek keberhasilan
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk membaca bahan berdasarkan ID resep
    public function readByRecipe() {
        $query = "SELECT i.id, i.nama, i.satuan, ri.jumlah 
                  FROM " . $this->table_name . " ri 
                  LEFT JOIN bahan i ON ri.bahan_id = i.id 
                  WHERE ri.resep_id = ? 
                  ORDER BY i.nama";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->resep_id);
        $stmt->execute();

        return $stmt;
    }

    // Fungsi untuk menghapus bahan dari resep berdasarkan ID resep dan ID bahan
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE resep_id = ? AND bahan_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->resep_id);
        $stmt->bindParam(2, $this->bahan_id);

        // Eksekusi query dan kembalikan true jika berhasil
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Fungsi untuk memperbarui jumlah bahan dalam resep
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET jumlah = :jumlah WHERE resep_id = :resep_id AND bahan_id = :bahan_id";
        $stmt = $this->conn->prepare($query);

        // Membersihkan data input
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));
        $this->resep_id = htmlspecialchars(strip_tags($this->resep_id));
        $this->bahan_id = htmlspecialchars(strip_tags($this->bahan_id));

        // Mengikat parameter
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":resep_id", $this->resep_id);
        $stmt->bindParam(":bahan_id", $this->bahan_id);

        // Eksekusi query dan kembalikan true jika berhasil
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
