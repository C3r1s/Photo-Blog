<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/client.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$postId = $input['postId'] ?? 0;
$userId = $_SESSION['user']['id'] ?? 0;
$role = $input['role'] ?? 'user';
$newLikesCount = $input['likes'] ?? null;

if (!$postId || !$userId) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing postId or userId']);
    exit;
}

if ($role === 'admin' && $newLikesCount !== null) {
    $response = callApi('POST', '/posts/admin-likes', [
        'postId' => $postId,
        'likes' => (int)$newLikesCount
    ]);

    if ($response['status'] === 200) {
        echo json_encode([
            'success' => true,
            'likes' => $response['data']['likes'],
            'message' => 'Admin likes updated'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to set admin likes',
            'debug' => $response
        ]);
    }
} else {
    $response = callApi('POST', '/posts/like', [
        'PostId' => $postId,
        'UserId' => $userId,
        'Role' => $role
    ]);

    if ($response['status'] === 200) {
        echo json_encode([
            'success' => true,
            'liked' => $response['data']['liked'] ?? false
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to toggle like',
            'debug' => $response
        ]);
    }
}