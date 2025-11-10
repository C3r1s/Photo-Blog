<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($username) ?> • PhotoBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

<?php require_once 'views/components/sidebar.php'; ?>

<div class="main-wrapper d-flex flex-column min-vh-100" style="margin-left: 250px;">
    <div class="main-content flex-grow-1">
        <div class="content-container">
            <div class="profile-header">
                <img src="<?= htmlspecialchars($avatar ?? 'https://via.placeholder.com/120') ?>" 
                     alt="Avatar" class="avatar">
                <div class="profile-info">
                    <h1><?= htmlspecialchars($username) ?></h1>
                    <div class="stats">
                        <span class="stat"><?= count($posts) ?> posts</span>
                    </div>
                    <div class="action-buttons">
                        <a href="edit-profile.php" class="btn btn-outline-light">Edit profile</a>
                    </div>
                </div>
            </div>

            <div class="post-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="post-item">
                        <?php if (!empty($post['imageUrl'])): ?>
                            <img src="<?= htmlspecialchars($post['imageUrl']) ?>" 
                                 class="post-image" alt="Post">
                        <?php else: ?>
                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <span class="text-muted">No image</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>

    <?php require_once 'views/components/footer.php'; ?>