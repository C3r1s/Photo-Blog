<?php
$postsResponse = callApi('GET', '/posts');
$posts = ($postsResponse['status'] === 200 && is_array($postsResponse['data'])) 
    ? $postsResponse['data'] 
    : [];

$isLoggedIn = isLoggedIn();
$user = $isLoggedIn ? getUser() : null;
?>