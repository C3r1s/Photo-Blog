<?php
require_once 'session.php';
require_once __DIR__ . '/api/client.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getUser();
if ($user === null) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$userId = $user['id'] ?? 0;
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$currentPage = 'create';

if (isset($data['ajax']) && $data['ajax'] === true) {
    $description = $data['description'] ?? '';
    $imageUrl = $data['image_url'] ?? '';

    if (empty($description) || empty($imageUrl)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Description and image are required']);
        exit;
    }

    $response = callApi('POST', '/posts', [
        'description' => $description,
        'imageUrl' => $imageUrl,
        'userId' => $userId
    ]);

    if ($response['status'] === 201) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create post.',
            'debug' => $response
        ]);
    }
    exit;
}

require_once 'views/components/sidebar.php';
require_once 'views/create-post.tmpl.php';
