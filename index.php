<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

$url = "https://api.botx.app/api/v1/correios/$codigo";

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

if (!isset($dados['tracking']['events']) || !is_array($dados['tracking']['events'])) {
    echo json_encode(['error' => 'Resposta inválida da API BotX.', 'raw' => $response]);
    exit;
}

$eventos = [];

foreach ($dados['tracking']['events'] as $evento) {
    $eventos[] = [
        'status' => $evento['status'],
        'data' => $evento['datetime'],
        'local' => $evento['location'] ?? '---'
    ];
}

echo json_encode($eventos);
