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

<?php if ($isLoggedIn): ?>
    <?php require_once 'views/components/sidebar.php'; ?>
<?php endif; ?>

<div class="main-wrapper d-flex flex-column min-vh-100" 
     style="<?= $isLoggedIn ? 'margin-left: 250px;' : '' ?>">
    <div class="main-content flex-grow-1">
        <div class="content-container">
            <?php if (empty($posts)): ?>
                <div class="text-center py-5">
                    <p class="text-white opacity-75 fs-5">No posts yet. Be the first to share!</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($posts as $post): ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$isLoggedIn): ?>
                <div class="text-center mt-4">
                    <a href="login.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="register.php" class="btn btn-light">Register</a>
                </div>
            <?php endif; ?>
        </div> 
        </div>

    <?php require_once 'views/components/footer.php'; ?>