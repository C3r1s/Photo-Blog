<?php
require_once 'session.php';
require_once 'api/client.php';
require_once 'api/index_logic.php';
?>

<?php require_once 'views/components/header.php'; ?>

<!-- Main Content -->
<main class="container py-3">
    <?php if (empty($posts)): ?>
    <div class="text-center py-5">
        <p class="no-posts-text">No posts yet. Be the first to share!</p>
    </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card post-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong><?= htmlspecialchars($post['username'] ?? 'Anonymous') ?></strong>
                            <small class="text-muted">
                                <?= date('d.m.Y H:i', strtotime($post['createdAt'] ?? 'now')) ?>
                            </small>
                        </div>
                        <?php if (!empty($post['imageUrl'])): ?>
                            <img src="<?= htmlspecialchars($post['imageUrl']) ?>" 
                                 class="post-image" alt="Post image">
                        <?php endif; ?>
                        <div class="card-body">
                            <p class="card-text"><?= htmlspecialchars($post['description'] ?? '') ?></p>
                            <div class="d-flex align-items-center">
                                <span class="text-danger me-2">❤️ <?= (int)($post['likes'] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once 'views/components/footer.php'; ?>