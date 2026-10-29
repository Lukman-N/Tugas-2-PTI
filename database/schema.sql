-- Sistem Informasi Manajemen Data Mahasiswa
CREATE DATABASE IF NOT EXISTS sim_mahasiswa
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE sim_mahasiswa;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(60) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS students (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nim VARCHAR(20) NOT NULL,
  nama VARCHAR(100) NOT NULL,
  program_studi VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telepon VARCHAR(20) NOT NULL DEFAULT '',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_students_nim (nim),
  KEY idx_students_nama (nama),
  KEY idx_students_program (program_studi)
) ENGINE=InnoDB;

-- Akun demo: username admin, password password.
-- Ganti hash ini sebelum aplikasi dipakai di lingkungan lain.
INSERT IGNORE INTO users (username, password_hash)
VALUES ('admin', '$2y$10$ABvcDi9.8QO8zCDN7uVJ5OvoI8ZwpGdb1.0rHrdiXFXT/LVo5X/hO');

INSERT IGNORE INTO students (nim, nama, program_studi, email, telepon) VALUES
  ('24410001', 'Alya Putri Ramadhani', 'Sistem Informasi', 'alya.ramadhani@example.test', '0812-3456-7801'),
  ('24410002', 'Bima Saputra', 'Teknik Informatika', 'bima.saputra@example.test', '0812-3456-7802'),
  ('24410003', 'Citra Maharani', 'Manajemen Informatika', 'citra.maharani@example.test', '0812-3456-7803'),
  ('24410004', 'Daffa Pratama', 'Sistem Informasi', 'daffa.pratama@example.test', '0812-3456-7804'),
  ('24410005', 'Elena Putri', 'Teknik Informatika', 'elena.putri@example.test', '0812-3456-7805');
