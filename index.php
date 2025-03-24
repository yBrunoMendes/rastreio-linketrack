<?php
header('Content-Type: application/json');

// Token de acesso gerado (substitua pelo token obtido)
$token = 'eyJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3NDI4NTExNTQsImlzcyI6InRva2VuLXNlcnZpY2UiLCJleHAiOjE3NDI5Mzc1NTQsImp0aSI6IjRiM2Q4Njk4LWY3YmYtNDhkMy1iNzI2LThlZWMxN2VhYTE1NyIsImFtYmllbnRlIjoiUFJPRFVDQU8iLCJwZmwiOiJQRiIsImlwIjoiNDUuMTY5LjIyNS4xNCwgMTkyLjE2OC4xLjEzMSIsImNhdCI6IklkMCIsImNwZiI6IjE2OTc2NTkwNzE0IiwiaWQiOiIxNjk3NjU5MDcxNCJ9.dJvGbIv4Ds2fAK-kT5hHDA3AR_JgdAU-tx5cQZd7RX4Ecw9Oyd2JzN2USs_HwUksUdq1DZWentLa1B8q3kj0ZTpqzOlABPsI6HHuzPpxUe3Yj_Rmwbfz0k7JJevgwhrvIaikeKYhjCwZC44fZjSsWwhVrQIErxDEx-16okKUO6LVDhXYnh5eE7ElVCfoMP6NSfxRW5QuAlWfpvhyRx8OsuyjfDWz9zEtSisASFqjN9C7IQi5sQBUl5m3m7CPnyNhpykxHCYFesjR_sNPl_6BrniGc1dSecJfDicvlScN96xP_hX2ynBMwDJWAkDuAby3sNswftuELqpmZk1eAxVNTA'; // Coloque o token obtido da geração

// Código de rastreio
$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// URL do rastreio
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

// Executa a requisição de rastreio
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
