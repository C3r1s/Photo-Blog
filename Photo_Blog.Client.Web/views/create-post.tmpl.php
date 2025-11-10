<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post • Photogram</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" href="/assets/style.css">
</head>
<body data-user-id="<?= (int)$userId ?>">

<div class="main-wrapper d-flex flex-column min-vh-100" style="margin-left: 250px;">
    <div class="main-content flex-grow-1">
        <div class="content-container">
            <h2>Create New Post</h2>

            <div class="card bg-dark mb-4">
                <div class="card-body text-center">
                    <img id="imagePreview" 
                         src="https://via.placeholder.com/400x400?text=No+Image" 
                         alt="Preview" 
                         class="img-fluid rounded mb-3"
                         style="max-height: 400px; object-fit: cover;">
                    <input type="file" id="imageInput" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-primary" id="selectImageButton">Select Image</button>
                </div>
            </div>

            <form id="createPostForm">
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control bg-secondary text-white" rows="4" required></textarea>
                </div>
                <input type="hidden" id="imageUrlInput" name="image_url" required>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100">Publish Post</button>
                    <a href="index.php" class="btn btn-secondary w-100">Cancel</a>
                </div>
            </form>
        </div>
    </div>

<?php require_once 'views/components/footer.php'; ?>
