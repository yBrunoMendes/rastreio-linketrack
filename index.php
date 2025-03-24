<?php
header('Content-Type: application/json');

// O código de rastreio
$codigo = $_GET['codigo'] ?? null;
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NTYiLCJqdGkiOiJhOTRmN2M5MTJkZjY2YWY1NmY3NTU5ZTFmODg4OTEyNzU4MWY3ZGE4OTNkNzM1NDZkNTdjNTA0OTE2ZGVjYmYwNTI1YWY2MmRiZjg5MDlkYiIsImlhdCI6MTc0Mjg0ODIzMy4xNzU5NTMsIm5iZiI6MTc0Mjg0ODIzMy4xNzU5NTUsImV4cCI6MTc3NDM4NDIzMy4xNjYyLCJzdWIiOiI5ZTgyZDVlMy00MzI1LTQyNmEtOTIwNy1hMDdkMGFhMTRmNjQiLCJzY29wZXMiOlsiY2FydC1yZWFkIiwiY2FydC13cml0ZSIsImNvbXBhbmllcy1yZWFkIiwiY29tcGFuaWVzLXdyaXRlIiwiY291cG9ucy1yZWFkIiwiY291cG9ucy13cml0ZSIsIm5vdGlmaWNhdGlvbnMtcmVhZCIsIm9yZGVycy1yZWFkIiwicHJvZHVjdHMtcmVhZCIsInByb2R1Y3RzLWRlc3Ryb3kiLCJwcm9kdWN0cy13cml0ZSIsInB1cmNoYXNlcy1yZWFkIiwic2hpcHBpbmctY2FsY3VsYXRlIiwic2hpcHBpbmctY2FuY2VsIiwic2hpcHBpbmctY2hlY2tvdXQiLCJzaGlwcGluZy1jb21wYW5pZXMiLCJzaGlwcGluZy1nZW5lcmF0ZSIsInNoaXBwaW5nLXByZXZpZXciLCJzaGlwcGluZy1wcmludCIsInNoaXBwaW5nLXNoYXJlIiwic2hpcHBpbmctdHJhY2tpbmciLCJlY29tbWVyY2Utc2hpcHBpbmciLCJ0cmFuc2FjdGlvbnMtcmVhZCIsInVzZXJzLXJlYWQiLCJ1c2Vycy13cml0ZSIsIndlYmhvb2tzLXJlYWQiLCJ3ZWJob29rcy13cml0ZSIsIndlYmhvb2tzLWRlbGV0ZSIsInRkZWFsZXItd2ViaG9vayJdfQ.VsH5Q1NCJC8PAGPz5YntLZnSrTjMTjgHlVTbqWfuqZD7P2eJiVnac9tgRFZfo4tLTQMhfDa6JaciIdXiGInWWlB1Xr8YIcmNscH19hWQDqW6MkSUcEMlDfIYXMPl5xJRZgBWUCm4B5qz_Wwx7VvFsWMBzNkDSEgThh0beCjbHdSIyjUu-WioyqFhaeeFnweocSdJLyugMvANUmYy3Hzac1WrKcdaExkmscCLqTU8Ay_FeodT28O9R_8YGeVJ1606HATcjRo1KeEWlOff8ICWlRyTnJjIDUGaWXwkxDc0-R188ptYTLyBBR9oTjEBD61I6ph0bT71uXNvDA-AY6D2kpxJffpN68crpGMxncz7-KsP3XstaOHr_-24nzoFn9hENZGv_LcR13Qn1Tsg9tRQaLkHb4VlVogUji1mE6JrAJsKucMoRh4fQfyLEWA2Wh9Fc5KTOXL2ZBwAD3RshpWdZLC0KhuyFM8RnR9ukNle3SFc906zxnVnJY9NgDpRuSpANArwxHu-zCN_te5RqiArFKbcOStbsBg5lze_Nx_3TBqy_KxlvtUH3H8g0yD7r6fWLmw8UBdnauhO9W2m6HiP0JI3Q9NGDrkpWSSEEtjkz9pBZz9DZzv4DWHnaG2UbxgzQ-5jlusl_3XbNu1cqtAA52NEqHoS95DFkiHbJnBRb64'; // Substitua com o token fornecido

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// URL da API do Melhor Envio
$url = "https://sandbox.melhorenvio.com.br/api/v2/me/shipment/tracking";

// Cabeçalhos necessários
$headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
    'User-Agent: MacLand (balhiago13@gmail.com)' // Substitua com o nome da sua aplicação e email de contato
];

// Corpo da requisição com o código de rastreio
$data = [
    'orders' => [$codigo]
];

// Inicializa cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_TIMEOUT => 10
]);

// Executa a requisição
$response = curl_exec($ch);

// Verifica se houve erro
if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decodifica a resposta da API
$dados = json_decode($response, true);

// Verifica se há erro
if (isset($dados['error'])) {
    echo json_encode(['error' => 'Erro ao obter rastreio: ' . $dados['error']]);
    exit;
}

echo json_encode($dados);
