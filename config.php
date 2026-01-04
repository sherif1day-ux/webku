<?php
// config.php
// Konfigurasi domain dan token
if (basename($_SERVER['SCRIPT_FILENAME']) == 'config.php') {
    http_response_code(403);
    exit('Forbidden');
}
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: index.php?msg=Harus%20login%20admin%20dulu&status=error');
    exit;
}
$domains = [
    'buyerku.biz.id' => [
        'token' => 'Pkzviewxe3HeKoUllY24fmysK3MSlQtUKtIZhR9i',
        'api_key' => '9e031361eed4bd35ad30289e35d7f8df',
    ],
    'sherif.web.id' => [
        'token' => 'b3dca399e82ced31bffe89b91ec11382',
        'api_key' => 'qkb3_IJI4xUw7l5H1uPSlofKAPRvdLCfFjNevm1a',
    ],
    'she0rif.my.id' => [
        'token' => '3752e9094d5f465006369eb543c01b7f',
        'api_key' => '8HL5qS49MiAS5vHWgVbRYcMrxKiAMn6WWFmqNteN',
    ],
    '404network.web.id' => [
        'token' => 'b6a678e17689b820c8286cc0f6177f2d',
        'api_key' => 'sqKaRr27oOrbPw3nkjtNpJY38Kp-CsQJq7MRd3ex',
    ],
    'forumku.my.id' => [
        'token' => '5d3374bfbe9a00237d3a3427c6b20d58',
        'api_key' => 'uwnRCrLrvgWBEu01RLPrLbBgZ7Llp8NIA1k4tc-N',
    ],
    'networking.my.id' => [
        'token' => 'b693bfbd886b91aff1156ec04f39ce6b',
        'api_key' => 'Kn4JNEhekoo1jSinoGp8BxtZ0Qzy1zRqMpJ1_3y5',
    ],
    'sees.biz.id' => [
        'token' => '1387f817528f091ef57806700327ab7e',
        'api_key' => 'PsZHB28N8ATTHVTEeio2QtTJsgQtG_cKN6jbExz3',
    ],
];

// Iklan brutal di seluruh halaman
function showBrutalAds() {
    echo '<div style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;pointer-events:none;">';
    for ($i=0; $i<4; $i++) {
        echo '<div style="position:absolute;top:'.($i*25).'vh;left:'.($i%2==0?'5vw':'65vw').';width:300px;height:100px;">';
        echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6014794398141091" crossorigin="anonymous"></script>';
        echo '<ins class="adsbygoogle" style="display:block;width:300px;height:100px;" data-ad-client="ca-pub-6014794398141091" data-ad-slot="6511596042" data-ad-format="auto" data-full-width-responsive="true"></ins>';
        echo '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';
        echo '</div>';
    }
    echo '</div>';
}
