<?php
session_start();
require 'config.php';
showBrutalAds();
if (!isset($_SESSION['ads_count']) || $_SESSION['ads_count'] < 10) {
    header('Location: index.php?msg=Belum%20memenuhi%20syarat%20jadi%20admin&status=error');
    exit;
}
$admins = [];
$admin_file = 'admins.json';
if (file_exists($admin_file)) {
    $admins = json_decode(file_get_contents($admin_file), true) ?: [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_admin_pass'])) {
    $new_pass = trim($_POST['new_admin_pass']);
    if (strlen($new_pass) < 5) {
        $msg = 'Password minimal 5 karakter.';
    } else {
        $admin_id = 'admin_' . time();
        $admins[$admin_id] = password_hash($new_pass, PASSWORD_DEFAULT);
        file_put_contents($admin_file, json_encode($admins));
        $msg = 'Admin berhasil didaftarkan! Silakan login di halaman admin.';
        $_SESSION['ads_count'] = 0;
    }
} else {
    $msg = 'Input tidak valid.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 350px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        h2 { text-align: center; }
        .msg { margin-top: 20px; padding: 10px; border-radius: 4px; background: #e0ffe0; color: #155724; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Admin</h2>
        <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <a href="index.php" style="display:block;margin-top:20px;text-align:center;">Kembali ke Beranda</a>
    </div>
</body>
</html>
