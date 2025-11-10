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
<body data-user-id="<?= (int)($_SESSION['user']['id'] ?? 0) ?>" data-user-role="<?= $_SESSION['user']['role'] ?? 'user' ?>">

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
                        <div class="col-12">
                            <div class="post-card index">
                                <?php if (!empty($post['imageUrl'])): ?>
                                    <img src="<?= htmlspecialchars($post['imageUrl']) ?>" 
                                         class="post-image" alt="Post">
                                <?php endif; ?>
                        
                                <div class="card-body">
                                    <h6 class="mb-1"><?= htmlspecialchars($post['description'] ?? '') ?></h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-secondary">
                                            <?= htmlspecialchars($post['author']['username'] ?? 'Anonymous') ?>
                                        </small>
                                        <small class="text-secondary">
                                            <?= date('d.m.Y H:i', strtotime($post['createdAt'] ?? 'now')) ?>
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-danger">❤️ <?= (int)($post['likes'] ?? 0) ?></div>
                                        <?php if ($isLoggedIn): ?>
                                            <?php $currentUser = $_SESSION['user']; ?>
                                            <?php $canEdit = ($currentUser['role'] === 'admin') || 
                                                          (isset($post['userId']) && $post['userId'] == $currentUser['id']); ?>
                                            <?php if ($canEdit): ?>
                                                <div>
                                                    <button class="btn btn-sm btn-outline-light edit-post" data-id="<?= $post['id'] ?>">Edit</button>
                                                    <button class="btn btn-sm btn-outline-danger delete-post" data-id="<?= $post['id'] ?>">Delete</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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
