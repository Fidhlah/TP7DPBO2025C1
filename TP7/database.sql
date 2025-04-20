-- Buat database
CREATE DATABASE resep_db;
USE resep_db;

-- Buat tabel kategori
CREATE TABLE kategori (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    PRIMARY KEY (id)
);

-- Buat tabel bahan
CREATE TABLE bahan (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    satuan VARCHAR(50) NOT NULL,
    dibuat_pada DATETIME NOT NULL,
    PRIMARY KEY (id)
);

-- Buat tabel resep
CREATE TABLE resep (
    id INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    instruksi TEXT NOT NULL,
    waktu_persiapan INT NOT NULL,
    kategori_id INT NOT NULL,
    dibuat_pada DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);

-- Buat tabel penghubung resep-bahan
CREATE TABLE resep_bahan (
    resep_id INT NOT NULL,
    bahan_id INT NOT NULL,
    jumlah VARCHAR(50) NOT NULL,
    PRIMARY KEY (resep_id, bahan_id),
    FOREIGN KEY (resep_id) REFERENCES resep(id),
    FOREIGN KEY (bahan_id) REFERENCES bahan(id)
);

-- Isi data kategori
INSERT INTO kategori (nama, deskripsi) VALUES 
('Sarapan Nusantara', 'Menu pagi khas Indonesia'),
('Hidangan Utama', 'Masakan berat untuk makan siang atau malam'),
('Camilan Tradisional', 'Jajanan pasar dan camilan lokal'),
('Minuman Segar', 'Minuman khas dan menyegarkan'),
('Makanan Penutup', 'Hidangan manis setelah makan');

-- Isi data bahan
INSERT INTO bahan (nama, satuan, dibuat_pada) VALUES 
('Tepung Terigu', 'cangkir', NOW()),
('Gula Pasir', 'cangkir', NOW()),
('Telur Ayam', 'butir', NOW()),
('Santan', 'cangkir', NOW()),
('Mentega', 'sendok makan', NOW()),
('Garam', 'sendok teh', NOW()),
('Ayam', 'gram', NOW()),
('Nasi', 'piring', NOW()),
('Tomat', 'buah', NOW()),
('Bawang Merah', 'butir', NOW()),
('Bawang Putih', 'siung', NOW()),
('Minyak Goreng', 'sendok makan', NOW()),
('Cokelat Masak', 'gram', NOW()),
('Pasta Vanila', 'sendok teh', NOW()),
('Baking Powder', 'sendok teh', NOW());

-- Isi data resep
INSERT INTO resep (nama, instruksi, waktu_persiapan, kategori_id, dibuat_pada) VALUES 
('Pisang Goreng', 'Campur tepung, gula, dan santan. Celupkan pisang lalu goreng hingga kecoklatan.', 25, 3, NOW()),
('Ayam Bakar', 'Lumuri ayam dengan bumbu. Bakar hingga matang dan beraroma harum.', 45, 2, NOW()),
('Kue Cokelat Kukus', 'Campur bahan, kukus adonan hingga matang dan lembut.', 60, 5, NOW());

-- Isi data resep-bahan
INSERT INTO resep_bahan (resep_id, bahan_id, jumlah) VALUES 
(1, 1, '1'),  -- Pisang Goreng - Tepung
(1, 2, '1/4'),  -- Pisang Goreng - Gula
(1, 4, '1/2'),  -- Pisang Goreng - Santan
(1, 12, '3'),  -- Pisang Goreng - Minyak Goreng

(2, 7, '500'),  -- Ayam Bakar - Ayam
(2, 9, '2'),  -- Ayam Bakar - Tomat
(2, 10, '5'),  -- Ayam Bakar - Bawang Merah
(2, 11, '3'),  -- Ayam Bakar - Bawang Putih
(2, 12, '2'),  -- Ayam Bakar - Minyak Goreng
(2, 6, '1'),   -- Ayam Bakar - Garam

(3, 1, '2'),  -- Kue Cokelat Kukus - Tepung
(3, 2, '1.5'),  -- Gula Pasir
(3, 3, '3'),  -- Telur Ayam
(3, 5, '4'),  -- Mentega
(3, 13, '100'),  -- Cokelat Masak
(3, 14, '1'),  -- Pasta Vanila
(3, 15, '1');  -- Baking Powder
