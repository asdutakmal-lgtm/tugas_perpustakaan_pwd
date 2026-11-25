<?php
session_start();
require_once "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form_anggota.php");
    exit;
}

$errors = [];

$no_anggota    = isset($_POST["no_anggota"])    ? trim($_POST["no_anggota"])    : "";
$nama_lengkap  = isset($_POST["nama_lengkap"])  ? trim($_POST["nama_lengkap"])  : "";
$email         = isset($_POST["email"])         ? trim($_POST["email"])         : "";
$no_hp         = isset($_POST["no_hp"])         ? trim($_POST["no_hp"])         : "";
$jenis_anggota = isset($_POST["jenis_anggota"]) ? trim($_POST["jenis_anggota"]) : "";
$alamat        = isset($_POST["alamat"])        ? trim($_POST["alamat"])        : "";

// Validasi nomor anggota
if (empty($no_anggota)) {
    $errors[] = "Nomor anggota wajib diisi.";
} elseif (!ctype_digit($no_anggota)) {
    $errors[] = "Nomor anggota hanya boleh angka.";
} elseif (strlen($no_anggota) > 15) {
    $errors[] = "Nomor anggota maksimal 15 digit.";
}

// Validasi nama lengkap
if (empty($nama_lengkap)) {
    $errors[] = "Nama lengkap wajib diisi.";
} elseif (mb_strlen($nama_lengkap) > 100) {
    $errors[] = "Nama maksimal 100 karakter.";
}

// Validasi email
if (empty($email)) {
    $errors[] = "Email wajib diisi.";
} elseif (strlen($email) > 100) {
    $errors[] = "Email maksimal 100 karakter.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}

// Validasi nomor HP
if (empty($no_hp)) {
    $errors[] = "Nomor HP wajib diisi.";
} elseif (!ctype_digit($no_hp)) {
    $errors[] = "Nomor HP hanya boleh angka.";
} elseif (strlen($no_hp) < 10 || strlen($no_hp) > 13) {
    $errors[] = "Nomor HP harus 10 sampai 13 digit.";
}

// Validasi jenis anggota
$pilihan = ["Mahasiswa", "Dosen", "Tenaga Kependidikan"];
if (empty($jenis_anggota) || !in_array($jenis_anggota, $pilihan, true)) {
    $errors[] = "Jenis anggota wajib dipilih.";
}

// Validasi alamat
if (empty($alamat)) {
    $errors[] = "Alamat wajib diisi.";
}

// Jika ada error, kembali ke form
if (!empty($errors)) {
    $_SESSION["errors"] = $errors;
    $_SESSION["old"] = [
        "no_anggota"    => $no_anggota,
        "nama_lengkap"  => $nama_lengkap,
        "email"         => $email,
        "no_hp"         => $no_hp,
        "jenis_anggota" => $jenis_anggota,
        "alamat"        => $alamat,
    ];
    header("Location: form_anggota.php");
    exit;
}

// Simpan dengan prepared statement
$sql  = "INSERT INTO anggota (no_anggota, nama_lengkap, email, no_hp, jenis_anggota, alamat)
         VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    $_SESSION["errors"] = ["Gagal menyiapkan query: " . mysqli_error($koneksi)];
    header("Location: form_anggota.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "ssssss", $no_anggota, $nama_lengkap, $email, $no_hp, $jenis_anggota, $alamat);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION["sukses"] = "Data anggota berhasil disimpan.";
} else {
    if (mysqli_stmt_errno($stmt) == 1062) {
        $_SESSION["errors"] = ["Nomor anggota sudah terdaftar."];
    } else {
        $_SESSION["errors"] = ["Data gagal disimpan: " . mysqli_stmt_error($stmt)];
    }
    $_SESSION["old"] = [
        "no_anggota"    => $no_anggota,
        "nama_lengkap"  => $nama_lengkap,
        "email"         => $email,
        "no_hp"         => $no_hp,
        "jenis_anggota" => $jenis_anggota,
        "alamat"        => $alamat,
    ];
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);

header("Location: form_anggota.php");
exit;