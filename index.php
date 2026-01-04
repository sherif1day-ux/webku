<?php
session_start();
if (!isset($_SESSION['ads_count'])) {
    $_SESSION['ads_count'] = 0;
}
if (isset($_GET['ads']) && $_GET['ads'] === 'done') {
    $_SESSION['ads_count']++;
}
$can_register_admin = $_SESSION['ads_count'] >= 10;
require 'config.php';
showBrutalAds();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SubdoFree By Sherif</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f4f4f4 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 420px;
            margin: 60px auto;
            background: #fff;
            padding: 32px 24px 24px 24px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 1.5px 6px rgba(0,0,0,0.04);
            position: relative;
        }
        h2 {
            text-align: center;
            font-size: 2em;
            font-weight: 700;
            color: #2d3a5a;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        label {
            display: block;
            margin-top: 18px;
            font-weight: 500;
            color: #2d3a5a;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 7px;
            border-radius: 7px;
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            font-size: 1.08em;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            border-color: #6366f1;
            outline: none;
        }
        button {
            margin-top: 24px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1.15em;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px #6366f133;
            transition: background 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, #4338ca 0%, #2563eb 100%);
        }
        .result {
            margin-top: 20px;
            padding: 12px;
            border-radius: 7px;
            font-size: 1.08em;
            font-weight: 500;
        }
        .success {
            background: #e0ffe0;
            color: #155724;
            border: 1.5px solid #a7f3d0;
        }
        .error {
            background: #ffe0e0;
            color: #721c24;
            border: 1.5px solid #fca5a5;
        }
        .telegram-btn {
            display: inline-block;
            margin-top: 22px;
            padding: 10px 22px;
            background: linear-gradient(90deg, #229ed9 0%, #38bdf8 100%);
            color: #fff;
            border-radius: 7px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.08em;
            box-shadow: 0 2px 8px #229ed933;
            transition: background 0.2s;
        }
        .telegram-btn:hover {
            background: linear-gradient(90deg, #0ea5e9 0%, #0369a1 100%);
        }
        .running-text {
            width: 100%;
            background: #6366f1;
            color: #fff;
            font-weight: 600;
            font-size: 1.08em;
            padding: 7px 0;
            border-radius: 7px 7px 0 0;
            margin-bottom: 18px;
            overflow: hidden;
            position: absolute;
            top: -38px;
            left: 0;
        }
        .running-text span {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 7s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        @media (max-width: 600px) {
            .container { max-width: 98vw; margin: 10px; padding: 15px; }
            h2 { font-size: 1.3em; }
            label, input, select, button { font-size: 1em; }
            .running-text { font-size: 0.98em; top: -32px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="running-text"><span>Sherif Mode Developer</span></div>
        <!-- Iklan Google AdSense -->
        <div style="margin-bottom:18px;">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6014794398141091" crossorigin="anonymous"></script>
            <!-- Sherif ads 1 -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6014794398141091"
                 data-ad-slot="6511596042"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                 (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <h2>SubdoFree By Sherif</h2>
        <form method="POST" action="buat.php">
            <label for="subdomain">Nama Subdomain</label>
            <input type="text" id="subdomain" name="subdomain" required placeholder="contoh: demo">
            <label for="domain">Pilih Domain</label>
            <select id="domain" name="domain">
                <option value="buyerku.biz.id">buyerku.biz.id</option>
                <option value="sherif.web.id">sherif.web.id</option>
                <option value="she0rif.my.id">she0rif.my.id</option>
                <option value="404network.web.id">404network.web.id</option>
                <option value="forumku.my.id">forumku.my.id</option>
                <option value="networking.my.id">networking.my.id</option>
                <option value="sees.biz.id">sees.biz.id</option>
            </select>
            <label for="record_type">Tipe Record</label>
            <select id="record_type" name="record_type">
                <option value="A">A</option>
                <option value="AAAA">AAAA</option>
                <option value="CNAME">CNAME</option>
                <option value="TXT">TXT</option>
                <option value="MX">MX</option>
            </select>
            <label for="ip">IP/Value</label>
            <input type="text" id="ip" name="ip" required placeholder="contoh: 1.1.1.1 atau value record">
            <button type="submit">Buat Subdomain</button>
        </form>
        <?php if (isset($_GET['msg'])): ?>
            <div class="result <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <?php if ($_GET['status'] === 'success' && preg_match('/Subdomain berhasil dibuat: ([a-zA-Z0-9\-_.]+\.[a-zA-Z0-9\-_.]+)/', $_GET['msg'], $m)):
                    $subdo = $m[1]; ?>
                    <button id="copyBtn" style="margin-top:10px;padding:8px 18px;background:#6366f1;color:#fff;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Copy Subdomain</button>
                    <script>
                        document.getElementById('copyBtn').onclick = function() {
                            navigator.clipboard.writeText('<?php echo $subdo; ?>');
                            this.textContent = 'Copied!';
                            setTimeout(() => { this.textContent = 'Copy Subdomain'; }, 1500);
                        };
                    </script>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div style="margin-top:30px;text-align:center;">
            <?php if ($can_register_admin): ?>
                <form method="POST" action="register_admin.php" style="margin-bottom:10px;">
                    <label for="new_admin_pass">Buat Password Admin Baru</label>
                    <input type="password" id="new_admin_pass" name="new_admin_pass" required placeholder="Password admin baru">
                    <button type="submit" style="margin-top:10px;">Daftar Jadi Admin</button>
                </form>
            <?php else: ?>
                <div style="color:#6366f1;font-weight:600;">Tonton iklan <span id="adsCount"><?php echo $_SESSION['ads_count']; ?></span>/10 kali untuk bisa jadi admin</div>
                <a href="ads.php?admin=1" class="telegram-btn" style="margin-top:12px;display:inline-block;">Tonton Iklan untuk Jadi Admin</a>
            <?php endif; ?>
        </div>
        <div style="margin-top:20px;text-align:center;">
            <a href="https://t.me/uplxforce" target="_blank" class="telegram-btn">Join Channel Owner</a>
            <a href="admin.php" class="telegram-btn" style="margin-left:10px;background:linear-gradient(90deg,#6366f1 0%,#229ed9 100%);">Login Admin</a>
        </div>
    </div>
</body>
</html>
