<?php
header('Content-Type: application/json');

$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    echo json_encode(['error' => 'Código de rastreio não informado.']);
    exit;
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://www2.correios.com.br/sistemas/rastreamento/resultado.cfm',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query(['objetos' => $codigo]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
    ],
]);

$html = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Erro cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if (!str_contains($html, 'Dados do Objeto')) {
    echo json_encode(['error' => 'Código inválido ou sem eventos.']);
    exit;
}

preg_match_all('/<tr>\\s*<td[^>]*>(.*?)<\\/td>\\s*<td[^>]*>(.*?)<\\/td>\\s*<td[^>]*>(.*?)<\\/td>\\s*<\\/tr>/is', $html, $matches, PREG_SET_ORDER);

$eventos = [];

foreach ($matches as $match) {
    $data = trim(strip_tags($match[1]));
    $local = trim(strip_tags($match[2]));
    $status = trim(strip_tags($match[3]));

    if ($status && $data) {
        $eventos[] = [
            'status' => $status,
            'data' => $data,
            'local' => $local
        ];
    }
}

echo json_encode($eventos);
