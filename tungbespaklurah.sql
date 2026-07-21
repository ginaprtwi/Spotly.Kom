-- =========================================================================
-- 1. TABEL: Mahasiswa
-- File penyimpanan fisik: mahasiswa.sql (Harddisk)
-- Keterangan: Menyimpan data dasar mahasiswa UNIKOM yang dapat mengakses sistem.
-- =========================================================================
CREATE TABLE IF NOT EXISTS mahasiswa (
    nim VARCHAR(8) NOT NULL,
    nama_mahasiswa VARCHAR(80) NOT NULL,
    email_unikom VARCHAR(60) NOT NULL,
    password VARCHAR(12) NOT NULL,
    PRIMARY KEY (nim)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================================
-- 2. TABEL: Tempat Belajar
-- File penyimpanan fisik: tempat_belajar.sql (Harddisk)
-- Keterangan: Menyimpan daftar tempat belajar, lokasi, suasana, deskripsi, 
--             jam operasional, dan status pengajuannya.
-- =========================================================================
CREATE TABLE IF NOT EXISTS tempat_belajar (
    id_tempat INT NOT NULL AUTO_INCREMENT,
    nama_tempat VARCHAR(60) NOT NULL,
    kategori_tempat ENUM('Di kampus', 'Sekitar Kampus') NOT NULL,
    lokasi VARCHAR(30) NOT NULL,
    fasilitas VARCHAR(255) DEFAULT NULL, -- Menampung data multi-pilihan seperti 'AC, WIFI, Parkir, Mushola, Colokan'
    kategori_suasana ENUM('Tenang', 'Ramah Diskusi') NOT NULL,
    foto VARCHAR(255) DEFAULT NULL, -- Menyimpan path/URL file gambar
    deskripsi VARCHAR(255) DEFAULT NULL, -- Deskripsi singkat mengenai tempat
    jam_buka TIME DEFAULT NULL, -- Jam operasional buka
    jam_tutup TIME DEFAULT NULL, -- Jam operasional tutup
    status_tempat ENUM('Disetujui', 'Diajukan') NOT NULL,
    tgl_submit DATE NOT NULL,
    nim VARCHAR(8) NOT NULL,
    PRIMARY KEY (id_tempat),
    CONSTRAINT fk_tempat_mahasiswa FOREIGN KEY (nim) 
        REFERENCES mahasiswa (nim) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================================
-- 3. TABEL: Kunjungan
-- File penyimpanan fisik: kunjungan.sql (Harddisk)
-- Keterangan: Mencatat riwayat kunjungan mahasiswa ke suatu tempat belajar.
-- =========================================================================
CREATE TABLE IF NOT EXISTS kunjungan (
    id_kunjungan INT NOT NULL AUTO_INCREMENT,
    tgl_kunjungan DATE NOT NULL,
    nim VARCHAR(8) NOT NULL,
    id_tempat INT NOT NULL,
    PRIMARY KEY (id_kunjungan),
    CONSTRAINT fk_kunjungan_mahasiswa FOREIGN KEY (nim) 
        REFERENCES mahasiswa (nim) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    CONSTRAINT fk_kunjungan_tempat FOREIGN KEY (id_tempat) 
        REFERENCES tempat_belajar (id_tempat) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================================
-- 4. TABEL: Ulasan
-- File penyimpanan fisik: ulasan.sql (Harddisk)
-- Keterangan: Menyimpan ulasan dan rating dari mahasiswa yang pernah berkunjung.
--             Primary Key didefinisikan pada id_kunjungan (Relasi 1-to-1 dengan Kunjungan)
-- =========================================================================
CREATE TABLE IF NOT EXISTS ulasan (
    id_kunjungan INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5), -- Memastikan batasan rating 1 s.d. 5
    komentar VARCHAR(255) DEFAULT NULL,
    tgl_ulasan DATE NOT NULL,
    PRIMARY KEY (id_kunjungan),
    CONSTRAINT fk_ulasan_kunjungan FOREIGN KEY (id_kunjungan) 
        REFERENCES kunjungan (id_kunjungan) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================================
-- 5. TABEL: Vote
-- File penyimpanan fisik: vote.sql (Harddisk)
-- Keterangan: Menyimpan data voting/reaksi mahasiswa terhadap tempat belajar tertentu.
--             Dalam ERD, ini bertindak sebagai tabel penghubung (Composite Primary Key).
--             Sesuai penamaan spesifikasi, nilai ENUM diubah menjadi 'UPVOTE' / 'DOWNVOTE'.
-- =========================================================================
CREATE TABLE IF NOT EXISTS vote (
    jenis_vote ENUM('UPVOTE', 'DOWNVOTE') NOT NULL, -- Catatan: 'UPDOWN' pada spek disesuaikan ke standar fungsional 'DOWNVOTE'
    tgl_vote DATE NOT NULL,
    nim VARCHAR(8) NOT NULL,
    id_tempat INT NOT NULL,
    PRIMARY KEY (nim, id_tempat), -- Composite key untuk menghindari voting ganda dari mhs yang sama di satu tempat
    CONSTRAINT fk_vote_mahasiswa FOREIGN KEY (nim) 
        REFERENCES mahasiswa (nim) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT fk_vote_tempat FOREIGN KEY (id_tempat) 
        REFERENCES tempat_belajar (id_tempat) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================================
-- 6. TABEL: Laporan
-- File penyimpanan fisik: laporan.sql (Harddisk)
-- Keterangan: Menyimpan laporan situasi keramaian terkini dari mahasiswa untuk tempat tertentu.
-- =========================================================================
CREATE TABLE IF NOT EXISTS laporan (
    id_report INT NOT NULL AUTO_INCREMENT,
    kondisi ENUM('Sepi', 'Cukup Ramai', 'Penuh Banget') NOT NULL,
    waktu_report DATETIME NOT NULL,
    waktu_expired DATETIME NOT NULL,
    nim VARCHAR(8) NOT NULL,
    id_tempat INT NOT NULL,
    PRIMARY KEY (id_report),
    CONSTRAINT fk_laporan_mahasiswa FOREIGN KEY (nim) 
        REFERENCES mahasiswa (nim) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    CONSTRAINT fk_laporan_tempat FOREIGN KEY (id_tempat) 
        REFERENCES tempat_belajar (id_tempat) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;