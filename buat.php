<?php
// buat.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subdomain = strtolower(trim($_POST['subdomain'] ?? ''));
    $domain = $_POST['domain'] ?? '';
    $record_type = $_POST['record_type'] ?? 'A';
    $ip = trim($_POST['ip'] ?? '');

    if (!$subdomain || !$domain || !isset($domains[$domain]) || !$ip) {
        header('Location: index.php?msg=Input%20tidak%20valid&status=error');
        exit;
    }

    $token = $domains[$domain]['token'];
    $api_key = $domains[$domain]['api_key'];

    // Cloudflare integration
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

    $zone_id = getZoneId($domain, $token);
    if (!$zone_id) {
        header('Location: index.php?msg=Zone%20ID%20tidak%20ditemukan&status=error');
        exit;
    }

    $data = [
        'type' => $record_type,
        'name' => $subdomain,
        'content' => $ip,
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
        $msg = "Subdomain berhasil dibuat: $subdomain.$domain ($record_type: $ip)";
        header('Location: index.php?msg=' . urlencode($msg) . '&status=success');
    } else {
        $msg = $result['errors'][0]['message'] ?? 'Gagal membuat subdomain.';
        header('Location: index.php?msg=' . urlencode($msg) . '&status=error');
    }
    exit;
} else {
    header('Location: index.php');
    exit;
}
