<?php
require_once 'session.php';
require_once 'api/client.php';
require_once 'views/components/sidebar.php';

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
$username = $user['username'] ?? 'User';
$email = $user['email'] ?? '';
$avatar = $user['avatar'] ?? null;
$role = $user['role'] ?? 'user';
require_once 'views/edit-profile.tmpl.php'
?>

