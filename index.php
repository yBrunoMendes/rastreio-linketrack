<?php
header('Content-Type: application/json');

// Informações para gerar o token
$usuario = '16976590714';  // Substitua com seu usuário "Meu Correios"
$senha = 'aJPu3YxgpuCY1oyCcXHUrTJPJCptjH2nPOhqywj3';  // Substitua com seu código de acesso
$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// Requisição para gerar o token
$authUrl = 'https://api.correios.com.br/v1/autentica';
$authHeaders = [
    'Authorization: Basic ' . base64_encode("$usuario:$senha"),
    'Content-Type: application/json'
];

$authData = [
    'usuario' => $usuario,
    'senha' => $senha
];

// Inicia a requisição cURL para gerar o token
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $authUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => $authHeaders,
    CURLOPT_POSTFIELDS => json_encode($authData),
]);

// Executa a requisição para gerar o token
$tokenResponse = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro ao gerar token: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decodifica a resposta para obter o token
$tokenData = json_decode($tokenResponse, true);
$token = $tokenData['token'] ?? null;

if (!$token) {
    echo json_encode(['error' => 'Erro ao obter token dos Correios']);
    exit;
}

// Agora, use o token para consultar o rastreio
$rastreioUrl = "https://api.correios.com.br/v1/rastreio/$codigo";
$rastreioHeaders = [
    "Authorization: Bearer $token",
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
