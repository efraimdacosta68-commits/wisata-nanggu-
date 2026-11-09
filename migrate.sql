-- Jalankan HANYA jika database sudah terpasang sebelumnya
USE desa_tawun;
ALTER TABLE permohonan ADD hasil VARCHAR(100) NULL AFTER lampiran;
INSERT IGNORE INTO settings VALUES('fonnte_token','');
UPDATE settings SET v='087769065304' WHERE k='wa';

-- Slider foto beranda (bisa juga otomatis dibuat oleh config.php)
CREATE TABLE IF NOT EXISTS hero_slide(id INT AUTO_INCREMENT PRIMARY KEY,gambar VARCHAR(100),urut INT DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT INTO hero_slide(gambar,urut) SELECT v,1 FROM settings WHERE k='hero_bg' AND v<>'' AND NOT EXISTS(SELECT 1 FROM hero_slide);

-- Halaman detail wisata (otomatis juga dibuat oleh config.php)
ALTER TABLE wisata ADD jarak VARCHAR(120) NULL, ADD aktivitas TEXT NULL;
