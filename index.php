<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código não informado']);
    exit;
}

$user = 'teste';
$token = '1abcd00b2731640e886fb41a8a9671ad1434c599dbaa0a0de9a5aa619f29a83f';

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.linketrack.com/track/json?user={$user}&token={$token}&codigo=" . urlencode($codigo),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($curl);
if (curl_errno($curl)) {
    echo json_encode(['error' => curl_error($curl)]);
    curl_close($curl);
    exit;
}
curl_close($curl);

echo $response;
