<?php
require_once "koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Test Koneksi</title>
</head>
<body>
    <h2>Test Koneksi Database</h2>
    <p style="color:green;">
        Koneksi ke database <strong><?= htmlspecialchars($db); ?></strong> berhasil.
    </p>
</body>
</html>