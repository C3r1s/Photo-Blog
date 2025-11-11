<?php
require_once 'session.php';
require_once __DIR__ . '/api/auth.php';

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
        $response = registerUser($username, $email, $password);

        if (in_array($response['status'], [200, 201], true)) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed. Email or username may be taken.';
        }
    }
}

require_once 'views/register.tmpl.php';
