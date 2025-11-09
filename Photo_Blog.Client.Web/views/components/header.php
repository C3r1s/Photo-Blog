<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">📸 Photo Blog</a>
        <div class="navbar-nav ms-auto">
            <?php if ($isLoggedIn): ?>
                <span class="navbar-text me-3">
                    Hello, <strong><?= htmlspecialchars($user['username'] ?? 'User') ?></strong>!
                </span>
                <a class="btn btn-outline-light" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn btn-outline-light me-2" href="login.php">Login</a>
                <a class="btn btn-light" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container mt-4">