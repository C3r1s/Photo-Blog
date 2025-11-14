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

$userLikes = $isLoggedIn ? ($_SESSION['liked_posts'] ?? []) : [];

if ($isLoggedIn):
    $currentPage = 'home';
    require_once 'views/components/sidebar.php';
endif; 

$viewData = [
    'posts' => $posts,
    'userLikes' => $userLikes,
    'isLoggedIn' => $isLoggedIn,
    'user' => $user,
    'userId' => $userId,
    'username' => $username,
    'email' => $email,
    'avatar' => $avatar,
];

extract($viewData);
require_once 'views/index.tmpl.php';
?>