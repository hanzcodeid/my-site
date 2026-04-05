<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "hkHpuCmN3sUr_GRqmah6nCf9SPSx4LvUw8";
$apiSecret = "KQ8TzsBVoUGFWsYTtZ6kcb";

$domain = isset($_GET['domain']) ? $_GET['domain'] : '';

if (empty($domain)) {
    echo json_encode(["error" => "Domain tidak boleh kosong"]);
    exit;
}

$url = "https://api.godaddy.com/v1/domains/available?domain=" . urlencode($domain);

$headers = [
    "Authorization: sso-key $apiKey:$apiSecret",
    "Accept: application/json"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo json_encode([
        "domain" => $domain,
        "available" => isset($data['available']) ? $data['available'] : false,
        "price" => 350000,
        "currency" => "IDR"
    ]);
} else {
    echo json_encode([
        "domain" => $domain,
        "available" => false,
        "error" => "Gagal mengecek domain"
    ]);
}
?>