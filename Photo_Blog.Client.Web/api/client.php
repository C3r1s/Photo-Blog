<?php
function callApi(string $method, string $path, ?array $data = null): array
{
    $url = 'http://localhost:5262' . $path; // ← замени на реальный URL при развёртывании
    $options = [
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'ignore_errors' => true,
        ]
    ];

    if ($data !== null) {
        $options['http']['content'] = json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $httpStatus = (int)substr($http_response_header[0], 9, 3);

    $decoded = $result ? json_decode($result, true) : null;
    return ['status' => $httpStatus, 'data' => $decoded];
}
?>