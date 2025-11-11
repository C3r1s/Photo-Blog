<?php
function callApi(string $method, string $path, ?array $data = null): array
{
    $baseUrl = 'http://localhost:5262';
    $url = $baseUrl . $path;

    $headers = [
        'Content-Type: application/json; charset=utf-8',
        'Accept: application/json'
    ];

    $opts = [
        'http' => [
            'method' => strtoupper($method),
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
            'timeout' => 10
        ]
    ];

    if ($data !== null) {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $opts['http']['content'] = $json;
        $opts['http']['header'] .= "\r\nContent-Length: " . strlen($json);
    }

    $context = stream_context_create($opts);
    $result = @file_get_contents($url, false, $context);

    $statusLine = $http_response_header[0] ?? 'HTTP/1.1 500 Internal Server Error';
    preg_match('{HTTP/\S*\s(\d{3})}', $statusLine, $match);
    $status = isset($match[1]) ? (int)$match[1] : 500;

    $decoded = $result ? json_decode($result, true) : null;

    return [
        'status' => $status,
        'data' => $decoded,
    ];
}
