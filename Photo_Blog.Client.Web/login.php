<?php
require_once 'session.php';
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://localhost:5262/users/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $email, 'password' => $password]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $user = json_decode($response, true);
        error_log("API response: " . print_r($user, true));
         
        if ($user && !empty($user['id'])) {
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
    }

    $error = 'Invalid email or password.';
}
require_once 'views/login.tmpl.php'
?>

