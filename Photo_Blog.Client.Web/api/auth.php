<?php
require_once __DIR__ . '/client.php';

function loginUser(string $email, string $password): array
{
    return callApi('POST', '/users/login', [
        'email' => $email,
        'password' => $password
    ]);
}

function registerUser(string $username, string $email, string $password): array
{
    return callApi('POST', '/users', [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'avatar' => null,
        'followers' => 0
    ]);
}
