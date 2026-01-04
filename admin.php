<?php
// admin.php
session_start();
$show_warning = false;
if (!isset($_SESSION['ads_count']) || $_SESSION['ads_count'] < 10) {
    $show_warning = true;
}
if ($show_warning && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Warning Admin</title>
        <style>
            body { font-family: Arial; background: #f4f4f4; }
            .container { max-width: 350px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px #0001; text-align:center; }
            .warning { color: #c00; font-weight:700; margin-bottom:18px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="warning">Anda harus menonton iklan 10x sebelum bisa login admin!</div>
            <div style="margin-bottom:18px;">
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6014794398141091" crossorigin="anonymous"></script>
                <ins class="adsbygoogle"
                     style="display:block;width:320px;height:100px;"
                     data-ad-client="ca-pub-6014794398141091"
                     data-ad-slot="6511596042"
                     data-ad-format="auto"
                     data-full-width-responsive="true"></ins>
                <script>
                     (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <a href="index.php" style="display:inline-block;padding:10px 22px;background:#6366f1;color:#fff;border-radius:7px;text-decoration:none;font-weight:600;">Kembali</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Ganti password admin di sini
$admin_password = null;

// Ambil semua admin dari local data
$admins = [];
$admin_file = 'admins.json';
if (file_exists($admin_file)) {
    $admins = json_decode(file_get_contents($admin_file), true) ?: [];
}

if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        $input_pass = $_POST['password'];
        $valid = false;
        foreach ($admins as $admin_id => $hash) {
            if (password_verify($input_pass, $hash)) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin_id;
                header('Location: admin.php');
                exit;
            }
        }
        $error = 'Password salah!';
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Login Admin</title>
        <style>
            body { font-family: Arial; background: #f4f4f4; }
            .container { max-width: 350px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
            h2 { text-align: center; }
            input { width: 100%; padding: 8px; margin-top: 10px; border-radius: 4px; border: 1px solid #ccc; }
            button { margin-top: 20px; width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
            button:hover { background: #0056b3; }
            .error { color: #c00; margin-top: 10px; text-align: center; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Login Admin</h2>
            <form method="POST">
                <input type="password" name="password" placeholder="Password admin" required>
                <button type="submit">Login</button>
            </form>
            <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}
require 'config.php';
showBrutalAds();

function getZoneId($domain, $token) {
    $ch = curl_init("https://api.cloudflare.com/client/v4/zones?name=$domain");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);
    if (isset($result['result'][0]['id'])) {
        return $result['result'][0]['id'];
    }
    return false;
}

// Proses tambah/hapus subdomain
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $domain = $_POST['domain'] ?? '';
    $subdomain = strtolower(trim($_POST['subdomain'] ?? ''));
    $token = $_POST['token'] ?? '';
    $api_key = $_POST['api_key'] ?? '';
    if ($domain && $subdomain && $token) {
        $zone_id = getZoneId($domain, $token);
        if (!$zone_id) {
            $msg = 'Zone ID tidak ditemukan.';
        } else if ($action === 'add') {
            // Tambah subdomain (A record ke 1.1.1.1, bisa diganti sesuai kebutuhan)
            $data = [
                'type' => 'A',
                'name' => $subdomain,
                'content' => '1.1.1.1',
                'ttl' => 3600,
                'proxied' => false
            ];
            $ch = curl_init("https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ]);
            $response = curl_exec($ch);
            $result = json_decode($response, true);
            curl_close($ch);
            if (isset($result['success']) && $result['success']) {
                $msg = "Subdomain berhasil ditambah: $subdomain.$domain";
            } else {
                $msg = $result['errors'][0]['message'] ?? 'Gagal menambah subdomain.';
            }
        } elseif ($action === 'delete') {
            // Cari record ID
            $ch = curl_init("https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records?type=A&name=$subdomain.$domain");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response, true);
            if (isset($result['result'][0]['id'])) {
                $record_id = $result['result'][0]['id'];
                // Hapus record
                $ch = curl_init("https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$record_id");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Authorization: Bearer $token",
                    "Content-Type: application/json"
                ]);
                $response = curl_exec($ch);
                $del_result = json_decode($response, true);
                curl_close($ch);
                if (isset($del_result['success']) && $del_result['success']) {
                    $msg = "Subdomain berhasil dihapus: $subdomain.$domain";
                } else {
                    $msg = $del_result['errors'][0]['message'] ?? 'Gagal menghapus subdomain.';
                }
            } else {
                $msg = 'Record tidak ditemukan.';
            }
        } else {
            $msg = 'Input tidak lengkap.';
        }
    } else {
        $msg = 'Input tidak lengkap.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin Subdomain</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        .container { max-width: 500px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px #0001; }
        h2 { text-align: center; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; width: 48%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button.delete { background: #c00; margin-left: 4%; }
        button:hover { background: #0056b3; }
        .msg { margin-top: 20px; padding: 10px; border-radius: 4px; background: #e0ffe0; color: #155724; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Panel Admin Subdomain</h2>
        <form method="POST">
            <label for="domain">Domain</label>
            <input type="text" id="domain" name="domain" required placeholder="contoh: buyerku.biz.id">
            <label for="subdomain">Subdomain</label>
            <input type="text" id="subdomain" name="subdomain" required placeholder="contoh: demo">
            <label for="token">Token Cloudflare</label>
            <input type="text" id="token" name="token" required placeholder="Token CF">
            <label for="api_key">API Key</label>
            <input type="text" id="api_key" name="api_key" required placeholder="API Key">
            <button type="submit" name="action" value="add">Tambah Subdomain</button>
            <button type="submit" name="action" value="delete" class="delete">Hapus Subdomain</button>
        </form>
        <?php if ($msg): ?>
            <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
