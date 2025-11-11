<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/client.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

header('Content-Type: application/json');

$uploadDir = __DIR__ . '/../uploads/avatars/';
$userId = $_SESSION['user']['id'] ?? 0;

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
    exit;
}

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Cannot create upload directory']);
        exit;
    }
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

$ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
if (!$ext) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File has no extension']);
    exit;
}

$allowed = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array(strtolower($ext), $allowed)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file type']);
    exit;
}

$filename = "avatar_{$userId}." . strtolower($ext);
$filepath = $uploadDir . $filename;

if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'File save failed. Check permissions.']);
    exit;
}

$avatarUrl = "/uploads/avatars/{$filename}";
$_SESSION['user']['avatar'] = $avatarUrl;

$userData = $_SESSION['user'];
$apiResponse = callApi('PUT', '/users/profile', [
    'Id' => $userData['id'],
    'Username' => $userData['username'],
    'AvatarUrl' => $avatarUrl
]);
$success = $apiResponse['status'] >= 200 && $apiResponse['status'] < 300;

echo json_encode([
    'success' => $success,
    'avatarUrl' => $avatarUrl
]);

