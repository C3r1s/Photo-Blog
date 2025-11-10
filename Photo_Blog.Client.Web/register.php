<?php
require_once 'session.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:5262/users');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'avatar' => null,
            'followers' => 0
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed. Email or username may be taken.';
        }
    }
}
require_once 'views/register.tmpl.php'
?>