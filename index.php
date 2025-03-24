<?php
header('Content-Type: application/json');

// Usuário e código de acesso (substitua com suas credenciais)
$usuario = '16976590714';  // Substitua pelo seu usuário
$senha = 'Lb230622.';      // Substitua pelo seu código de acesso

// URL para gerar o token
$url = 'https://api.correios.com.br/v1/autentica';

// Cabeçalhos da requisição
$headers = [
    'Authorization: Basic ' . base64_encode("$usuario:$senha"),
    'Content-Type: application/json'
];

// Dados para a requisição (não são necessários parâmetros extras para este endpoint)
$data = [];

// Inicia a requisição cURL para gerar o token
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_POSTFIELDS => json_encode($data),
]);

// Executa a requisição
$response = curl_exec($ch);

// Verifica se ocorreu erro
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro ao gerar token: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decodifica a resposta para obter o token
$tokenData = json_decode($response, true);
$token = $tokenData['token'] ?? null;

if (!$token) {
    echo json_encode(['error' => 'Erro ao obter token']);
    exit;
}

echo json_encode(['token' => $token]);
