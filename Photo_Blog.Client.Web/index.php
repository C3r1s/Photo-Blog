<?php
require_once 'session.php';
require_once 'api/client.php';

$postsResponse = callApi('GET', '/posts');
$posts = ($postsResponse['status'] === 200 && is_array($postsResponse['data'])) 
    ? $postsResponse['data'] 
    : [];

$isLoggedIn = isLoggedIn();
require_once 'views/index.tmpl.php'
?>

