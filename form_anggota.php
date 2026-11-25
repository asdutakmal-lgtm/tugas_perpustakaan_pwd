<?php
session_start();

$errors = isset($_SESSION["errors"]) ? $_SESSION["errors"] : [];
$sukses = isset($_SESSION["sukses"]) ? $_SESSION["sukses"] : "";
$old    = isset($_SESSION["old"])    ? $_SESSION["old"]    : [];
unset($_SESSION["errors"], $_SESSION["sukses"], $_SESSION["old"]);

$no_anggota    = isset($old["no_anggota"])    ? $old["no_anggota"]    : "";
$nama_lengkap  = isset($old["nama_lengkap"])  ? $old["nama_lengkap"]  : "";
$email         = isset($old["email"])         ? $old["email"]         : "";
$no_hp         = isset($old["no_hp"])         ? $old["no_hp"]         : "";
$jenis_anggota = isset($old["jenis_anggota"]) ? $old["jenis_anggota"] : "";
$alamat        = isset($old["alamat"])        ? $old["alamat"]        : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Anggota</title>
</head>
<body>

<h2>Form Pendaftaran Anggota Perpustakaan</h2>

<?php if (!empty($sukses)): ?>
    <p style="color:green;"><?= htmlspecialchars($sukses); ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <p style="color:red;">Data gagal disimpan karena:</p>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="proses_anggota.php">

    <label>Nomor Anggota</label><br>
    <input type="text" name="no_anggota" maxlength="15"
           value="<?= htmlspecialchars($no_anggota); ?>" required>
    <br><br>

    <label>Nama Lengkap</label><br>
    <input type="text" name="nama_lengkap" maxlength="100"
           value="<?= htmlspecialchars($nama_lengkap); ?>" required>
    <br><br>

    <label>Email</label><br>
    <input type="email" name="email" maxlength="100"
           value="<?= htmlspecialchars($email); ?>" required>
    <br><br>

    <label>Nomor HP</label><br>
    <input type="text" name="no_hp" minlength="10" maxlength="13"
           value="<?= htmlspecialchars($no_hp); ?>" required>
    <br><br>

    <label>Jenis Anggota</label><br>
    <select name="jenis_anggota" required>
        <option value="">-- Pilih Jenis Anggota --</option>
        <option value="Mahasiswa" <?= $jenis_anggota == "Mahasiswa" ? "selected" : ""; ?>>Mahasiswa</option>
        <option value="Dosen" <?= $jenis_anggota == "Dosen" ? "selected" : ""; ?>>Dosen</option>
        <option value="Tenaga Kependidikan" <?= $jenis_anggota == "Tenaga Kependidikan" ? "selected" : ""; ?>>Tenaga Kependidikan</option>
    </select>
    <br><br>

    <label>Alamat</label><br>
    <textarea name="alamat" required><?= htmlspecialchars($alamat); ?></textarea>
    <br><br>

    <button type="submit">Simpan</button>

</form>

</body>
</html>