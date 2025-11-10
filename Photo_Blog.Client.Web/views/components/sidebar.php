<?php
require_once __DIR__ . '/../../session.php';
$user = getUser();
$isLoggedIn = $user !== null;
?>

<div class="sidebar bg-dark text-white d-flex flex-column" style="width: 250px; min-height: 100vh; position: fixed; left: 0; top: 0; border-right: 1px solid #333;">
    <div class="p-3">
        <h5 class="mb-4">Photogram</h5>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="/index.php" class="nav-link text-white d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.53 2.29 6.5 5.47 7.59.5.09 1 .12 1.524.12.56 0 1.11-.04 1.654-.12C10.71 14.5 13 11.53 13 8c0-3.58-3.58-7.16-7.16-7.16zm1.438 7.16a.5.5 0 00-.5-.5H6.5a.5.5 0 00-.5.5v2a.5.5 0 00.5.5h1.438a.5.5 0 00.5-.5V7.16z"/>
                    </svg>
                    Home
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M15.5 14a6.5 6.5 0 00-13 0h13z"/>
                        <path d="M8 1a2 2 0 00-2 2v1.5a1 1 0 001 1h2a1 1 0 001-1V3a2 2 0 00-2-2z"/>
                    </svg>
                    Search
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 118 1a7 7 0 010 14zm0-1.5a.5.5 0 00.5-.5V11a.5.5 0 00-.5-.5H6.5a.5.5 0 00-.5.5v2.5a.5.5 0 00.5.5H8z"/>
                    </svg>
                    Messages
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 118 1a7 7 0 010 14zm0-1.5a.5.5 0 00.5-.5V11a.5.5 0 00-.5-.5H6.5a.5.5 0 00-.5.5v2.5a.5.5 0 00.5.5H8z"/>
                    </svg>
                    Notifications
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/create-post.php" class="nav-link text-white d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 118 1a7 7 0 010 14zm0-1.5a.5.5 0 00.5-.5V11a.5.5 0 00-.5-.5H6.5a.5.5 0 00-.5.5v2.5a.5.5 0 00.5.5H8z"/>
                    </svg>
                    Create
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/profile.php" class="nav-link text-white d-flex align-items-center active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 118 1a7 7 0 010 14zm0-1.5a.5.5 0 00.5-.5V11a.5.5 0 00-.5-.5H6.5a.5.5 0 00-.5.5v2.5a.5.5 0 00.5.5H8z"/>
                    </svg>
                    Profile
                </a>
            </li>
        </ul>
    </div>
    <div class="mt-auto p-3">
        <button class="btn btn-outline-light w-100" onclick="location.href='/logout.php'">Logout</button>
    </div>
</div>