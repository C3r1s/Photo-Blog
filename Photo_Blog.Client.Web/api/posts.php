<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/client.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$action = $data['action'] ?? '';

switch ($action) {
    case 'get':
        $postId = $data['id'] ?? 0;
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing post ID']);
            exit;
        }

        $apiResponse = callApi('POST', '/posts/get', ['Id' => $postId]);

        if ($apiResponse['status'] === 200 && $apiResponse['data']) {
            echo json_encode($apiResponse['data']);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Post not found',
                'debug' => $apiResponse
            ]);
        }
        break;

    case 'delete':
        $postId = $data['id'] ?? 0;
        if (!$postId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing post ID']);
            exit;
        }

        $apiResponse = callApi('POST', '/posts/delete', [
            'Id' => $postId,
            'UserId' => $_SESSION['user']['id'],
            'Role' => $_SESSION['user']['role'] ?? 'user'
        ]);

        if ($apiResponse['status'] === 200) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Delete failed',
                'debug' => $apiResponse
            ]);
        }
        break;

    case 'update':
        $postId = $data['id'] ?? 0;
        $description = trim($data['description'] ?? '');
        $imageUrl = $data['imageUrl'] ?? '';

        if (!$postId || !$description) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Missing data']);
            exit;
        }

        $apiResponse = callApi('POST', '/posts/update', [
            'Id' => $postId,
            'Description' => $description,
            'ImageUrl' => $imageUrl,
            'UserId' => $_SESSION['user']['id'],
            'Role' => $_SESSION['user']['role'] ?? 'user'
        ]);

        if ($apiResponse['status'] === 200) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Update failed',
                'debug' => $apiResponse
            ]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid action: ' . $action]);
        break;
}
?>