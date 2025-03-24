<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;
$clientId = '17996';  // Substitua pelo seu Client ID
$clientSecret = 'MnCKir77FNps80EzHijMtqv00oHk0fDHfij440kA';  // Substitua pelo seu Secret

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// Autenticação para obter o token
$authUrl = "https://api.melhorenvio.com.br/auth/token";
$authData = [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'grant_type' => 'client_credentials'
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $authUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($authData),
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro cURL ao obter token: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);
$tokenData = json_decode($response, true);
$accessToken = $tokenData['access_token'] ?? null;

if (!$accessToken) {
    echo json_encode(['error' => 'Não foi possível obter o token de autenticação.']);
    exit;
}

// Usando o token para acessar a API de rastreio
$url = "https://api.melhorenvio.com.br/rastreio/$codigo?access_token=$accessToken";

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

if (isset($dados['error'])) {
    echo json_encode(['error' => 'Erro ao obter rastreio: ' . $dados['error']]);
    exit;
}

echo json_encode($dados);
