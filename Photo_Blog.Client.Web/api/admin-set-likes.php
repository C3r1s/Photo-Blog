<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/client.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$user = getUser();
if ($user['role'] !== 'admin') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Admin access required']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$postId = $input['postId'] ?? 0;
$likes = $input['likes'] ?? null;

if (!$postId || $likes === null) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing postId or likes']);
    exit;
}

$response = callApi('POST', '/posts/admin-likes', [
    'postId' => $postId,
    'likes' => (int)$likes
]);

if ($response['status'] === 200) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'likes' => $response['data']['likes']
    ]);
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'Failed to update admin likes',
        'debug' => $response
    ]);
}