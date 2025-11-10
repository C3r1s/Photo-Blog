<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function getUser(): ?array {
    return $_SESSION['user'] ?? null;
}
?>