<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYmNlYmJlMDVlNGJiZDcwMzFlMmJmMzZlNTMyMTllMzMwNjgyMTAzOTZlMmNmNDY5NmYzODcxYWE4YTY0ZWUxNmU1YmQyMjc2MTRhNTVlNjIiLCJpYXQiOjE3NDI4NDc4NjYuOTEyOTA0LCJuYmYiOjE3NDI4NDc4NjYuOTEyOTA2LCJleHAiOjE3NzQzODM4NjYuODk5NjMzLCJzdWIiOiI5ZTgyYzZmZi1mYTEzLTRmNDItOTJhMS02ZGIzZGUzMzg1MzQiLCJzY29wZXMiOlsiY2FydC1yZWFkIiwiY2FydC13cml0ZSIsImNvbXBhbmllcy1yZWFkIiwiY29tcGFuaWVzLXdyaXRlIiwiY291cG9ucy1yZWFkIiwiY291cG9ucy13cml0ZSIsIm5vdGlmaWNhdGlvbnMtcmVhZCIsIm9yZGVycy1yZWFkIiwicHJvZHVjdHMtcmVhZCIsInByb2R1Y3RzLWRlc3Ryb3kiLCJwcm9kdWN0cy13cml0ZSIsInB1cmNoYXNlcy1yZWFkIiwic2hpcHBpbmctY2FsY3VsYXRlIiwic2hpcHBpbmctY2FuY2VsIiwic2hpcHBpbmctY2hlY2tvdXQiLCJzaGlwcGluZy1jb21wYW5pZXMiLCJzaGlwcGluZy1nZW5lcmF0ZSIsInNoaXBwaW5nLXByZXZpZXciLCJzaGlwcGluZy1wcmludCIsInNoaXBwaW5nLXNoYXJlIiwic2hpcHBpbmctdHJhY2tpbmciLCJlY29tbWVyY2Utc2hpcHBpbmciLCJ0cmFuc2FjdGlvbnMtcmVhZCIsInVzZXJzLXJlYWQiLCJ1c2Vycy13cml0ZSIsIndlYmhvb2tzLXJlYWQiLCJ3ZWJob29rcy13cml0ZSIsIndlYmhvb2tzLWRlbGV0ZSIsInRkZWFsZXItd2ViaG9vayJdfQ.byDBKQXZO-_WrOmWASlKmCFhIedaZu3yRQzsf2eb_J0LcTKez8Ml9sQHBABVaD8Ph2ryw-h8lQ8lkx67S-hJRCLBQdJiaYeqlUpmMfDvHQ00Mt2ZnG_s4d8kNFzlYt3ebd9eHvG_qZIoiU66nkNg1UNBmJK3KPt0FQki5q3R2KejJspRmFPh7d9Zj8ZjOqbFHXbJ8p7xjGbag2M3oXINdx3XmCIe7ueVoKQM8Dsi-Lc8vlM0J13CYu6LvC72q06euiY4JKZPlkwQHwmfT03QsORilqnq2LaSAmplIncZ-i8VYooQ00AjescP6ITZNO73MEa5OhXpv2uuV1d9dMNT9W4PGMsZ1H5wOyT_JgXqZViDaAYCWBxLbJIL4B1-EpYJFR_3NBYRiRi3ErIPVmXprRy2lgE4gnmqGXCiTxK-b3R2jh52yHO5_QGOcGlXNMYn9cmdBzeg-4hF-0mdJ9ulccQPGC6q_h4kk4IWOETu2XLQawj_xPNyDQKUL4EJ8G0YoHABJayJZ7ptCbPJ0dZ95-dbMJJzlHgAEX3GMvelvh0Mk9cSlGdERfvU0g7NgLUidu761NPnNp6KZOkNhQAygXDPcC1YkhgqmHeEJjeYV_bgk3S65Msm66v-1YHZF71Y1eLM5dGEkju9Ls51NfW0Pku8o6Gf_1-3Q8fRbY7tZxU'; // Substitua com seu token de acesso

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

// Usando o token para acessar a API de rastreio
$url = "https://api.melhorenvio.com.br/rastreio/$codigo?access_token=$token";

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
