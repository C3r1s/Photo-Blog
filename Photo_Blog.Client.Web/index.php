<?php
require_once 'session.php';
require_once 'api/client.php';

$postsResponse = callApi('GET', '/posts');
$posts = ($postsResponse['status'] === 200 && is_array($postsResponse['data'])) 
    ? $postsResponse['data'] 
    : [];
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
$isLoggedIn = isLoggedIn();
if ($isLoggedIn):
require_once 'views/components/sidebar.php';
endif; 
require_once 'views/index.tmpl.php'
?>

