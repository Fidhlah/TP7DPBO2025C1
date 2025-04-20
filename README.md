# TP7DPBO2025C1 - JANJI
Saya Muhammad Hafidh Fadhilah dengan NIM 2305672 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# Desain Program
![Screenshot 2025-04-20 231501](https://github.com/user-attachments/assets/05471dde-0ef8-444b-befd-812fc3bcbee6)
## Struktur Tabel
- **kategori**: Menyimpan jenis kategori resep seperti sarapan, hidangan utama, camilan, dll.
- **bahan**: Menyimpan daftar bahan makanan lengkap dengan satuan dan waktu penambahan.
- **resep**: Menyimpan informasi resep seperti nama, instruksi pembuatan, waktu persiapan, dan relasi ke kategori.
- **resep_bahan**: Tabel many-to-many yang menghubungkan resep dengan bahan-bahan yang digunakan, termasuk jumlahnya.

# Alur kode
1. **Koneksi Database:**
   - File `config/database.php` membuat koneksi PDO ke database `resep_db`.

2. **Model:**
   - Folder `models/` berisi class-class yang merepresentasikan tabel di database.
   - Setiap class punya fungsi untuk CRUD (create, read, update, delete).
   - `ResepBahan.php` menghubungkan resep dengan bahan beserta jumlahnya.

3. **Routing & Tampilan:**
   - File `index.php` menjadi halaman awal yang mengarahkan ke halaman lain.
   - Folder `views/` memuat tampilan:
     - `categories/` → untuk kategori
     - `ingredients/` → untuk bahan
     - `recipes/` → untuk resep
  - Folder includes berisi header dan footer yang diinclude di tiap halaman.

# Dokumentasi video (CRUD resep bahan ada saat menambah/mengedit resep karena bentuknya many to many)
https://github.com/user-attachments/assets/1529ea2e-7997-4e7c-bb09-41c469a8cc57

