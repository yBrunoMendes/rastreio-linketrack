<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

$url = "https://api.postmon.com.br/v1/rastreio/correios/" . urlencode($codigo);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo json_encode(['error' => 'Erro cURL: ' . curl_error($curl)]);
    curl_close($curl);
    exit;
}

curl_close($curl);
$dados = json_decode($response, true);

echo json_encode($dados['eventos'] ?? []);
