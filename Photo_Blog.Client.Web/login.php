<?php
require_once 'session.php';
require_once __DIR__ . '/api/auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = loginUser($email, $password);

    if ($response['status'] === 200 && !empty($response['data']['id'])) {
        $user = $response['data'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'] ?? '',
            'email' => $user['email'] ?? '',
            'role' => $user['role'] ?? 'user',
            'avatar' => $user['avatar'] ?? null
        ];

        header('Location: index.php');
        exit;
    }

    $error = 'Invalid email or password.';
}

require_once 'views/login.tmpl.php';
