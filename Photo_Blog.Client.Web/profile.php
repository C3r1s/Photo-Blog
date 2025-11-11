<?php
require_once 'session.php';
require_once 'api/client.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getUser();
$currentPage = 'profile';

if ($user === null) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$userId = $user['id'] ?? 0;
$username = $user['username'] ?? 'User';
$email = $user['email'] ?? '';
$avatar = $user['avatar'] ?? null;

$postsResponse = callApi('GET', "/users/{$username}/posts");
$posts = ($postsResponse['status'] === 200 && is_array($postsResponse['data'])) 
    ? $postsResponse['data'] 
    : [];
    require_once 'views/components/sidebar.php';
    require_once 'views/profile.tmpl.php';
?>

