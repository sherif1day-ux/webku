<?php
session_start();
if (!isset($_SESSION['ads_count'])) {
    $_SESSION['ads_count'] = 0;
}
// Deteksi mode admin
$is_admin_mode = isset($_GET['admin']) && $_GET['admin'] == '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['ads_count']++;
    if ($is_admin_mode && $_SESSION['ads_count'] >= 10) {
        header('Location: register_admin.php');
        exit;
    }
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tonton Iklan</title>
</head>
<body style="margin:0;padding:0;">
    <form method="POST" id="adsForm">
        <div style="width:100vw;height:100vh;display:flex;align-items:center;justify-content:center;background:#fff;">
            <div>
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
                <button type="submit" id="nextBtn" style="margin-top:30px;padding:12px 32px;background:#6366f1;color:#fff;border:none;border-radius:8px;font-size:1.1em;font-weight:600;cursor:pointer;">Lanjut</button>
            </div>
        </div>
    </form>
    <script>
        // Otomatis klik tombol setelah 10 detik
        setTimeout(function(){
            document.getElementById('nextBtn').click();
        }, 10000);
    </script>
</body>
</html>
