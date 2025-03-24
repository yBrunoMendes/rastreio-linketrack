<?php
header('Content-Type: application/json');

// O token de acesso que você já tem
$token = 'aJPu3YxgpuCY1oyCcXHUrTJPJCptjH2nPOhqywj3'; // Seu token de acesso

// O código de rastreio
$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// URL para consultar o rastreio
$rastreioUrl = "https://api.correios.com.br/v1/rastreio/$codigo";
$rastreioHeaders = [
    "Authorization: Bearer $token", // Usando o token fornecido
    'Content-Type: application/json',
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $rastreioUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $rastreioHeaders,
]);

// Executa a requisição para o rastreio
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro cURL ao rastrear: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decodifica a resposta da API
$dados = json_decode($response, true);

if (isset($dados['error'])) {
    echo json_encode(['error' => 'Erro ao obter rastreio: ' . $dados['error']]);
    exit;
}

echo json_encode($dados);
