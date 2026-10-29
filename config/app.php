<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Jakarta');

if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_name('sim_mahasiswa_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!isset($_SESSION['language']) || !in_array($_SESSION['language'], ['id', 'en'], true)) {
    $_SESSION['language'] = 'id';
}

$translations = [
    'id' => [
        'app_name' => 'SIM Mahasiswa', 'app_tagline' => 'Sistem Informasi Manajemen',
        'dashboard' => 'Data Mahasiswa', 'nav_home' => 'Ringkasan', 'nav_add' => 'Tambah Data',
        'logout' => 'Keluar', 'switch_language' => 'English', 'page_subtitle' => 'Pantau dan kelola informasi mahasiswa dalam satu tempat.',
        'add_student' => 'Tambah Mahasiswa', 'total_students' => 'Total Mahasiswa',
        'study_programs' => 'Program Studi', 'database_status' => 'Status Database', 'connected' => 'Siap digunakan',
        'student_list' => 'Daftar Mahasiswa', 'list_hint' => 'Data mahasiswa yang tersimpan pada sistem.',
        'search_placeholder' => 'Cari nama, NIM, atau program studi...', 'search' => 'Cari', 'reset' => 'Reset',
        'nim' => 'NIM', 'name' => 'Nama Mahasiswa', 'program' => 'Program Studi', 'email' => 'Email',
        'phone' => 'Nomor Telepon', 'actions' => 'Aksi', 'edit' => 'Ubah', 'delete' => 'Hapus',
        'confirm_delete' => 'Hapus data mahasiswa ini?', 'empty_title' => 'Belum ada data mahasiswa',
        'empty_desc' => 'Tambahkan data pertama untuk mulai menggunakan sistem.', 'no_results' => 'Data tidak ditemukan.',
        'login_title' => 'Selamat datang', 'login_subtitle' => 'Masuk untuk mengelola data mahasiswa.',
        'username' => 'Nama pengguna', 'password' => 'Kata sandi', 'login' => 'Masuk',
        'demo_account' => 'Akun demo: admin / password', 'login_error' => 'Nama pengguna atau kata sandi tidak sesuai.',
        'logged_out' => 'Anda telah keluar dari sistem.', 'form_new_title' => 'Tambah Mahasiswa',
        'form_edit_title' => 'Ubah Data Mahasiswa', 'form_hint' => 'Isi informasi mahasiswa dengan benar.',
        'required' => 'Wajib diisi', 'optional' => 'Opsional', 'save' => 'Simpan Data', 'cancel' => 'Batal',
        'saved' => 'Data mahasiswa berhasil disimpan.', 'deleted' => 'Data mahasiswa berhasil dihapus.',
        'not_found' => 'Data mahasiswa tidak ditemukan.', 'invalid_id' => 'Identitas data tidak valid.',
        'invalid_nim' => 'NIM harus berisi 5 sampai 20 karakter huruf, angka, titik, garis bawah, atau tanda hubung.',
        'invalid_name' => 'Nama wajib diisi dan tidak boleh lebih dari 100 karakter.',
        'invalid_program' => 'Program studi wajib diisi dan tidak boleh lebih dari 100 karakter.',
        'invalid_email' => 'Masukkan alamat email yang valid.', 'invalid_phone' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda kurung, +, atau tanda hubung (7-20 karakter).',
        'duplicate_nim' => 'NIM tersebut sudah terdaftar.', 'csrf_error' => 'Sesi formulir tidak valid. Muat ulang halaman dan coba lagi.',
        'db_error' => 'Database belum dapat dihubungi. Periksa koneksi dan impor database/schema.sql.',
        'footer_note' => 'Tugas 2 - Penerapan Teknologi Internet', 'student_count' => 'data tercatat',
    ],
    'en' => [
        'app_name' => 'Student MIS', 'app_tagline' => 'Management Information System',
        'dashboard' => 'Student Records', 'nav_home' => 'Overview', 'nav_add' => 'Add Record',
        'logout' => 'Log out', 'switch_language' => 'Bahasa Indonesia', 'page_subtitle' => 'Monitor and manage student information in one place.',
        'add_student' => 'Add Student', 'total_students' => 'Total Students',
        'study_programs' => 'Study Programs', 'database_status' => 'Database Status', 'connected' => 'Ready to use',
        'student_list' => 'Student Directory', 'list_hint' => 'Student records stored in the system.',
        'search_placeholder' => 'Search name, student ID, or study program...', 'search' => 'Search', 'reset' => 'Reset',
        'nim' => 'Student ID', 'name' => 'Student Name', 'program' => 'Study Program', 'email' => 'Email',
        'phone' => 'Phone Number', 'actions' => 'Actions', 'edit' => 'Edit', 'delete' => 'Delete',
        'confirm_delete' => 'Delete this student record?', 'empty_title' => 'No student records yet',
        'empty_desc' => 'Add the first record to start using the system.', 'no_results' => 'No records found.',
        'login_title' => 'Welcome back', 'login_subtitle' => 'Sign in to manage student records.',
        'username' => 'Username', 'password' => 'Password', 'login' => 'Sign in',
        'demo_account' => 'Demo account: admin / password', 'login_error' => 'The username or password is incorrect.',
        'logged_out' => 'You have signed out.', 'form_new_title' => 'Add Student',
        'form_edit_title' => 'Edit Student Record', 'form_hint' => 'Enter the student information accurately.',
        'required' => 'Required', 'optional' => 'Optional', 'save' => 'Save Record', 'cancel' => 'Cancel',
        'saved' => 'Student record saved successfully.', 'deleted' => 'Student record deleted successfully.',
        'not_found' => 'Student record was not found.', 'invalid_id' => 'The record identifier is invalid.',
        'invalid_nim' => 'Student ID must be 5 to 20 letters, numbers, dots, underscores, or hyphens.',
        'invalid_name' => 'Name is required and must be at most 100 characters.',
        'invalid_program' => 'Study program is required and must be at most 100 characters.',
        'invalid_email' => 'Enter a valid email address.', 'invalid_phone' => 'Phone may contain digits, spaces, parentheses, +, or hyphens (7-20 characters).',
        'duplicate_nim' => 'That student ID is already registered.', 'csrf_error' => 'The form session is invalid. Reload the page and try again.',
        'db_error' => 'The database is unavailable. Check the connection and import database/schema.sql.',
        'footer_note' => 'Assignment 2 - Internet Technology Implementation', 'student_count' => 'records stored',
    ],
];

function t(string $key): string
{
    global $translations;
    $language = $_SESSION['language'] ?? 'id';
    return $translations[$language][$key] ?? $translations['id'][$key] ?? $key;
}

function h(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function post_string(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? $value : '';
}

function require_auth(): void
{
    if (empty($_SESSION['user_id'])) {
        redirect('login.php');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_is_valid(mixed $token): bool
{
    return is_string($token)
        && isset($_SESSION['_csrf_token'])
        && hash_equals($_SESSION['_csrf_token'], $token);
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'sim_mahasiswa';
    $user = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS');
    if ($password === false) {
        $password = '';
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
        return null;
    }
    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);
    return $flash;
}
