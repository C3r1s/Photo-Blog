<?php
require_once 'client.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

session_start();
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'update_profile') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $updateData = [
        'id' => $_SESSION['user']['id'],
        'username' => $input['username'] ?? $_SESSION['user']['username'],
        'avatar' => $input['avatar'] ?? $_SESSION['user']['avatar'],
    ];

    $response = callApi('PUT', '/users/profile', $updateData);
    
    if ($response['status'] === 200) {
        // Обновляем сессию
        $_SESSION['user']['username'] = $updateData['username'];
        $_SESSION['user']['avatar'] = $updateData['avatar'];
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
    }
}

if ($action === 'change_password') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $changeData = [
        'email' => $_SESSION['user']['email'],
        'oldPassword' => $input['oldPassword'] ?? '',
        'newPassword' => $input['newPassword'] ?? ''
    ];

    $response = callApi('PUT', '/users/password', $changeData);
    
    if ($response['status'] === 200) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Incorrect old password or update failed']);
    }
}
?>