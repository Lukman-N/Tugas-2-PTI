# Tugas 2 - Sistem Informasi Manajemen Data Mahasiswa

Aplikasi web sederhana untuk mengelola data mahasiswa menggunakan PHP murni, MySQL/MariaDB, PDO, dan session. Antarmuka tersedia dalam Bahasa Indonesia dan English.

## Fitur

- Login dan logout dengan session; halaman pengelolaan hanya dapat dibuka setelah login.
- Pilihan bahasa disimpan di session dan tetap digunakan saat berpindah halaman.
- Tambah, tampil, cari, ubah, dan hapus data mahasiswa.
- Validasi form di sisi server, prepared statements, escaping keluaran, dan token CSRF.
- Basis data berisi akun demo dan data mahasiswa contoh.

## Menjalankan dengan XAMPP

1. Salin folder proyek ke `xampp/htdocs/sim-mahasiswa`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Impor `database/schema.sql` melalui phpMyAdmin. Skrip akan membuat database `sim_mahasiswa`, tabel, akun demo, dan lima data mahasiswa.
4. Buka `http://localhost/sim-mahasiswa/public/login.php`.

Akun demo: `admin` / `password`.

Nilai koneksi bawaan ada di `config/app.php`: host `127.0.0.1`, port `3306`, database `sim_mahasiswa`, user `root`, dan password kosong. Sesuaikan konfigurasi atau gunakan environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASS` bila diperlukan.

## Struktur proyek

```text
config/app.php              Konfigurasi session, bahasa, PDO, helper, dan CSRF
database/schema.sql         Database, tabel, akun demo, dan data contoh
includes/header.php         Header aplikasi dan navigasi
includes/footer.php         Penutup halaman dan JavaScript ringan
public/                     Halaman PHP dan stylesheet
report/                     Laporan PDF A4 dan screenshot aplikasi
```

## Alur program

1. `login.php` menerima username dan password melalui POST. Aplikasi mencari username dengan query berparameter dan memeriksa password menggunakan `password_verify()`.
2. Setelah login berhasil, ID pengguna disimpan di session. Halaman CRUD memeriksa session sebelum menampilkan data atau form.
3. Form mahasiswa mengirim data dengan POST. Aplikasi memeriksa token CSRF, memvalidasi field, lalu memakai prepared statement untuk INSERT atau UPDATE.
4. `index.php` membaca data dan menampilkannya ke tabel. Nilai keluaran di-escape dengan `htmlspecialchars()`.
5. Penghapusan menggunakan POST dengan token CSRF; pilihan bahasa disimpan pada `$_SESSION['language']`.

## Pengamanan

- Session ID diperbarui setelah login; cookie memakai `HttpOnly`, `SameSite=Lax`, dan `Secure` saat HTTPS aktif.
- Query database memakai PDO prepared statements.
- Keluaran dinamis di-escape sebagai HTML.
- Email, NIM, nama, program studi, dan nomor telepon divalidasi di server.
- Aksi yang mengubah data memakai token CSRF.

## Laporan

Laporan PDF A4 memuat screenshot halaman login, dashboard Bahasa Indonesia, form mahasiswa, dan dashboard English. Screenshot diambil dari aplikasi yang berjalan di XAMPP menggunakan data contoh.
