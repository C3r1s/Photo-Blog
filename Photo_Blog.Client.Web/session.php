<?php
session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function getUser(): ?array {
    return $_SESSION['user'] ?? null;
}
?>