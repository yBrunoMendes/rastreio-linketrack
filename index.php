<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

$url = "https://api.postmon.com.br/v1/rastreio/correios/" . urlencode($codigo);

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

if (!isset($dados['eventos']) || !is_array($dados['eventos'])) {
    echo json_encode(['error' => 'Código inválido ou sem eventos']);
    exit;
}

echo json_encode($dados['eventos']);
