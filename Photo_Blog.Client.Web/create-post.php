<?php
require_once 'session.php';

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

if (isset($data['ajax']) && $data['ajax'] === true) {
    $description = $data['description'] ?? '';
    $imageUrl = $data['image_url'] ?? '';

    if (empty($description) || empty($imageUrl)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Description and image are required']);
        exit;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:5262/posts');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'description' => $description,
        'imageUrl' => $imageUrl,
        'userId' => $userId
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 201) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Failed to create post. HTTP ' . $httpCode]);
    }
    exit;
}
require_once 'views/components/sidebar.php';
require_once 'views/create-post.tmpl.php';
?>