<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile • PhotoBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body data-user-id="<?= (int)$userId ?>">
<?php require_once 'views/components/sidebar.php'; ?>

<div class="main-wrapper d-flex flex-column min-vh-100" style="margin-left: 250px;">
    <div class="main-content flex-grow-1">
        <div class="content-container">
            <h2 class="mb-4">Edit profile</h2>

            <!-- User Info Card -->
            <div class="card bg-dark mb-4">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img 
                            src="<?= htmlspecialchars($avatar ?? 'https://via.placeholder.com/60') ?>" 
                            alt="Avatar" 
                            class="rounded-circle me-3" 
                            width="60" 
                            height="60" 
                            id="avatarPreview">
                        <div>
                            <strong><?= htmlspecialchars($username) ?></strong><br>
                            <small class="text-secondary"><?= htmlspecialchars($email) ?></small>
                        </div>
                    </div>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-primary btn-sm" id="changePhotoBtn">Change photo</button>
                </div>
            </div>

            <form id="editProfileForm">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control bg-secondary text-white" 
                           id="username" value="<?= htmlspecialchars($username) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control bg-secondary text-white" 
                           id="email" value="<?= htmlspecialchars($email) ?>" required>
                    <div class="form-text text-secondary">5 / 150</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select bg-secondary text-white" id="role" disabled>
                        <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <div class="form-text text-secondary">This won't be part of your public profile.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password (optional)</label>
                    <input type="password" class="form-control bg-secondary text-white" 
                           id="newPassword" placeholder="Leave blank to keep current password">
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="button" class="btn btn-primary w-100" id="changePasswordBtn">Change password</button>
                    <button type="button" class="btn btn-primary w-100" id="saveChangesBtn">Submit</button>
                </div>
            </form>
        </div>
</div>
    <?php require_once 'views/components/footer.php'; ?>
