<?php
require_once 'client.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_GET['action'] ?? '';

    if ($action === 'login') {
        $input = json_decode(file_get_contents('php://input'), true);
        $response = callApi('POST', '/users/login', $input);

        if ($response['status'] === 200 && !empty($response['data'])) {
            $user = $response['data'];

            // Сохраняем в сессию
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            $_SESSION['avatar'] = $user['avatar'];

            echo json_encode(['success' => true]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Неверный email или пароль']);
        }
    }

    if ($action === 'register') {
        $input = json_decode(file_get_contents('php://input'), true);
        $response = callApi('POST', '/users', $input);

        if ($response['status'] === 200 || $response['status'] === 201) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            $error = $response['data']['error'] ?? 'Ошибка регистрации';
            echo json_encode(['success' => false, 'error' => $error]);
        }
    }
}
?>