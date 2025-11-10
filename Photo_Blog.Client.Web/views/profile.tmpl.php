<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($username) ?> • Photogram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="main-wrapper d-flex flex-column min-vh-100" style="margin-left: 150px;">
    <div class="main-content flex-grow-1">
        <div class="container-fluid px-3 py-2">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="profile-header-card bg-dark text-white p-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= htmlspecialchars($avatar ?? 'https://via.placeholder.com/160') ?>" 
                                 alt="Avatar" class="rounded-circle">
                            <div>
                                <h1 class="mb-1"><?= htmlspecialchars($username) ?></h1>
                                <p class="text-secondary mb-2"><?= count($posts) ?> posts</p>
                                <a href="edit-profile.php" class="btn btn-outline-light">Edit profile</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text">
                                Good men mean well. They're just don't always end up doing well. - Isaac Clarke<br>
                                @<?= htmlspecialchars($username) ?>
                            </p>
                        </div>
                    </div>

                    <div class="post-grid">
                        <?php foreach ($posts as $post): ?>
                            <div class="post-item">
                                <div class="post-item-container">
                            
                                    <div class="post-image-wrapper">
                                        <?php if (!empty($post['imageUrl'])): ?>
                                            <img src="<?= htmlspecialchars($post['imageUrl']) ?>" 
                                                 alt="Post Image">
                                        <?php else: ?>
                                            <div class="placeholder-image" style="height: 100%;">
                                                <span class="text-muted">No image</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                            
                                    <div class="post-content-bottom">
                                        <?php if (!empty($post['description'])): ?>
                                            <div class="post-description">
                                                <p class="text-white" style="font-size: 0.95rem; line-height: 1.4; font-weight: normal; margin: 0;">
                                                    <?= htmlspecialchars($post['description']) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                            
                                        <?php if (!empty($post['createdAt'])): ?>
                                            <div class="post-time">
                                                <small class="text-info" style="font-size: 0.8rem; font-weight: 500;">
                                                    <?= date('M j, Y g:i A', strtotime($post['createdAt'])) ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                            
                                        <?php if ($isLoggedIn): ?>
                                            <?php $currentUser = $_SESSION['user']; ?>
                                            <?php $canEdit = ($currentUser['role'] === 'admin') || 
                                                          (isset($post['userId']) && $post['userId'] == $currentUser['id']); ?>
                                            <?php if ($canEdit): ?>
                                                <div class="d-flex justify-content-center gap-3 mt-2">
                                                    <button class="btn btn-sm btn-outline-light edit-post" data-id="<?= $post['id'] ?>">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger delete-post" data-id="<?= $post['id'] ?>">
                                                        Delete
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                            
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once 'views/components/footer.php'; ?>
