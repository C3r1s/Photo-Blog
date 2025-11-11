<?php
require_once __DIR__ . '/../session.php';

if (!isLoggedIn()) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

header('Content-Type: application/json');

$uploadDir = __DIR__ . '/../uploads/posts/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Cannot create upload directory']);
        exit;
    }
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file uploaded']);
    exit;
}

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
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

$filename = 'post_' . uniqid() . '.' . strtolower($ext);
$filepath = $uploadDir . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $filepath)) {
    $imageUrl = "/uploads/posts/{$filename}";
    echo json_encode(['success' => true, 'imageUrl' => $imageUrl]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'File save failed']);
}
?>