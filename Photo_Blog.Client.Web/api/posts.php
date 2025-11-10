<?php
require_once __DIR__ . '/../session.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$action = $data['action'] ?? '';

if ($action === 'get') {
    $postId = $data['id'] ?? 0;
    error_log($postId);
    if (!$postId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Missing post ID']);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:5262/posts/get");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['Id' => $postId]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    error_log($response);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
       
    if ($httpCode === 200) {
        header('Content-Type: application/json');
        echo $response;
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Post not found']);
    }
    exit;
}

if ($action === 'delete') {
    $postId = $data['id'] ?? 0;
    if (!$postId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Missing post ID']);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:5262/posts/delete");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'Id' => $postId,
        'UserId' => $_SESSION['user']['id'],
        'Role' => $_SESSION['user']['role'] ?? 'user'
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_get_info($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Delete failed']);
    }
    exit;
}

if ($action === 'update') {
    $postId = $data['id'] ?? 0;
    $description = $data['description'] ?? '';
    $imageUrl = $data['imageUrl'] ?? '';

    if (!$postId || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Missing data']);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost:5262/posts/update");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'Id' => $postId,
        'Description' => $description,
        'ImageUrl' => $imageUrl,
        'UserId' => $_SESSION['user']['id'],
        'Role' => $_SESSION['user']['role'] ?? 'user'
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Update failed']);
    }
    exit;
}

http_response_code(400);
header('Content-Type: application/json');
echo json_encode(['success' => false, 'error' => 'Invalid action: ' . $action]);
?>