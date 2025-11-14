<?php

require_once __DIR__ . '/../session.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? null;
$postId = (int)($input['postId'] ?? 0);

if (!$action || !$postId) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Missing action or postId']);
    exit;
}

if (!isset($_SESSION['liked_posts'])) {
    $_SESSION['liked_posts'] = [];
}

if ($action === 'add') {
    if (!in_array($postId, $_SESSION['liked_posts'])) {
        $_SESSION['liked_posts'][] = $postId;
    }
} elseif ($action === 'remove') {
    $_SESSION['liked_posts'] = array_filter($_SESSION['liked_posts'], function($id) use ($postId) {
        return $id !== $postId;
    });
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);