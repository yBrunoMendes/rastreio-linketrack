<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

$url = "https://linketrack.com/track/json?user=teste&token=1abcd00b2731640e886fb41a8a9671ad1434c599dbaa0a0de9a5aa619f29a83f&codigo=" . urlencode($codigo);

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);
$dados = json_decode($response, true);

echo json_encode($dados['eventos'] ?? []);
