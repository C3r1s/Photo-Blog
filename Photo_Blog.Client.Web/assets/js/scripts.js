const userId = document.body.dataset.userId ? parseInt(document.body.dataset.userId) : null;
const userRole = document.body.dataset.userRole || 'user';

const saveChangesBtn = document.getElementById('saveChangesBtn');
if (saveChangesBtn && userId) {
    saveChangesBtn.addEventListener('click', async () => {
        const username = document.getElementById('username')?.value;
        const email = document.getElementById('email')?.value;

        if (!username || !email) {
            alert('Username and email are required.');
            return;
        }

        try {
            const res = await fetch('/api/profile.php?action=update_profile', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: userId, username, avatar: null, email})
            });

            const data = await res.json();
            if (data.success) {
                window.location.href = '/profile.php';
            } else {
                alert('Error: ' + data.error + data.toString());
            }
        } catch (err) {
            console.error('Update profile error:', err);
            console.error(err.toString());
            alert('Network error during profile update');
        }
    });
}

const changePasswordBtn = document.getElementById('changePasswordBtn');
if (changePasswordBtn) {
    changePasswordBtn.addEventListener('click', async () => {
        const oldPassword = prompt("Enter your current password:");
        const newPassword = document.getElementById('newPassword')?.value;

        if (!oldPassword || !newPassword) {
            alert('Both fields are required.');
            return;
        }

        try {
            const res = await fetch('/api/profile.php?action=change_password', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({oldPassword, newPassword})
            });

            const data = await res.json();
            if (data.success) {
                alert('Password changed!');
                document.getElementById('newPassword').value = '';
            } else {
                alert('Error: ' + data.error);
            }
        } catch (err) {
            console.error('Change password error:', err);
            alert('Network error during password change');
        }
    });
}

const changePhotoBtn = document.getElementById('changePhotoBtn');
const avatarInput = document.getElementById('avatarInput');
if (changePhotoBtn && avatarInput) {
    changePhotoBtn.addEventListener('click', () => avatarInput.click());

    avatarInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('avatar', file);

        try {
            const res = await fetch('/api/upload-avatar.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                const avatarPreview = document.getElementById('avatarPreview');
                if (avatarPreview) {
                    avatarPreview.src = data.avatarUrl + '?t=' + Date.now();
                }
            } else {
                alert('Upload failed: ' + data.error);
            }
        } catch (err) {
            console.error('Avatar upload error:', err);
            alert('Network error during avatar upload');
        }
    });
}

let currentPostId = null;
const deleteButtons = document.querySelectorAll('.delete-post');
const editButtons = document.querySelectorAll('.edit-post');
const deleteModalEl = document.getElementById('deletePostModal');
const editModalEl = document.getElementById('editPostModal');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
const saveEditBtn = document.getElementById('saveEditBtn');

if (deleteButtons.length && deleteModalEl && confirmDeleteBtn) {
    const deleteModal = new bootstrap.Modal(deleteModalEl);

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            currentPostId = btn.dataset.id;
            if (currentPostId) deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', async () => {
        if (!currentPostId) return;

        try {
            const res = await fetch('/api/posts.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'delete',
                    id: parseInt(currentPostId),
                    role: userRole
                })
            });

            if (res.ok) {
                deleteModal.hide();
                location.reload();
            } else {
                const errorText = await res.text();
                alert('Failed to delete post: ' + (errorText || 'Unknown error'));
            }
        } catch (err) {
            console.error('Delete post error:', err);
            alert('Network error during post deletion');
        }
    });
}

if (editButtons.length && editModalEl && saveEditBtn) {
    const editModal = new bootstrap.Modal(editModalEl);
    let currentEditImageUrl = '';

    editButtons.forEach(btn => {
        btn.addEventListener('click', async () => {
            currentPostId = btn.dataset.id;
            if (!currentPostId) return;

            try {
                const res = await fetch('/api/posts.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'get',
                        id: parseInt(currentPostId),
                        role: userRole
                    })
                });

                if (!res.ok) {
                    const errorData = await res.json();
                    alert('Post not found: ' + (errorData.error || 'ID: ' + errorData.received_id));
                    return;
                }

                const post = await res.json();
                currentEditImageUrl = post.imageUrl || '';
                document.getElementById('editDescription').value = post.description || '';
                document.getElementById('editPreview').src = currentEditImageUrl || 'https://via.placeholder.com/400x400?text=No+Image';
                editModal.show();
            } catch (err) {
                console.error('Load post error:', err);
                alert('Failed to load post data');
            }
        });
    });

    const selectImageButton = document.getElementById('selectImageButton');
    const imageInput = document.getElementById('imageInputEdit');
    const imagePreview = document.getElementById('editPreview');

    if (selectImageButton && imageInput && imagePreview) {
        selectImageButton.addEventListener('click', () => {
            imageInput.click();
        });

        imageInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch('/api/upload-post-image.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();
                if (data.success && data.imageUrl) {
                    currentEditImageUrl = data.imageUrl;
                    imagePreview.src = data.imageUrl + '?t=' + Date.now();
                } else {
                    alert('Upload failed: ' + (data.error || 'Unknown error'));
                }
            } catch (err) {
                console.error('Image upload error:', err);
                alert('Network error during image upload');
            }
        });
    }

    saveEditBtn.addEventListener('click', async () => {
        if (!currentPostId) return;

        const description = document.getElementById('editDescription').value.trim();
        if (!description) {
            alert('Description is required');
            return;
        }

        try {
            const res = await fetch('/api/posts.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'update',
                    id: parseInt(currentPostId),
                    description,
                    imageUrl: currentEditImageUrl
                })
            });

            if (res.ok) {
                editModal.hide();
                location.reload();
            } else {
                const errorData = await res.json();
                alert('Failed to update post: ' + (errorData.error || 'Unknown error'));
            }
        } catch (err) {
            console.error('Update post error:', err);
            alert('Network error during post update');
        }
    });
}

const imageInput = document.getElementById('imageInput');
const createPostForm = document.getElementById('createPostForm');
const selectImageButton = document.getElementById('selectImageButton');

if (imageInput && createPostForm && selectImageButton) {
    selectImageButton.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        try {
            const res = await fetch('/api/upload-post-image.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            if (data.success && data.imageUrl) {
                const imageUrlInput = document.getElementById('imageUrlInput');
                const imagePreview = document.getElementById('imagePreview');
                if (imageUrlInput) imageUrlInput.value = data.imageUrl;
                if (imagePreview) imagePreview.src = data.imageUrl + '?t=' + Date.now();
            } else {
                alert('Upload failed: ' + (data.error || 'Unknown error'));
            }
        } catch (err) {
            console.error('Post image upload error:', err);
            alert('Network error during image upload');
        }
    });

    createPostForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const imageUrl = document.getElementById('imageUrlInput')?.value;
        const description = document.querySelector('textarea[name="description"]')?.value;

        if (!imageUrl || !description) {
            alert('Please fill in all fields');
            return;
        }

        try {
            const res = await fetch('/create-post.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    description,
                    image_url: imageUrl,
                    ajax: true
                })
            });

            const data = await res.json();
            if (data.success) {
                window.location.href = '/index.php';
            } else {
                alert('Failed to create post: ' + (data.error || 'Unknown error'));
            }
        } catch (err) {
            console.error('Create post error:', err);
            alert('Network error during post creation');
        }
    });
}

document.querySelectorAll('.like-post').forEach(button => {
    button.addEventListener('click', async (e) => {
        e.preventDefault();
        const postId = parseInt(button.dataset.postId);
        const currentLikes = parseInt(button.dataset.likes) || 0;
        const isLiked = button.classList.contains('liked');

        try {
            const res = await fetch('/api/like-post.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    postId: postId,
                    userId: userId,
                    role: userRole
                })
            });

            if (res.ok) {
                const data = await res.json();

                if (data.success && data.liked !== undefined) {
                    if (data.liked) {
                        button.classList.add('liked');
                        let newLikes = currentLikes + 1;
                        button.dataset.likes = newLikes;

                        const countSpan = button.querySelector('.like-count');
                        if (countSpan) {
                            countSpan.textContent = newLikes;
                        } else {
                            const span = document.createElement('span');
                            span.className = 'like-count ms-1';
                            span.textContent = newLikes;
                            button.appendChild(span);
                        }

                        fetch('/api/update-session-likes.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({action: 'add', postId: postId})
                        });
                    } else {
                        button.classList.remove('liked');
                        let newLikes = Math.max(0, currentLikes - 1);
                        button.dataset.likes = newLikes;

                        const countSpan = button.querySelector('.like-count');
                        if (countSpan) {
                            if (newLikes === 0) {
                                countSpan.remove();
                            } else {
                                countSpan.textContent = newLikes;
                            }
                        }

                        fetch('/api/update-session-likes.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({action: 'remove', postId: postId})
                        });
                    }
                } else {
                    console.error('Server response error:', data.error);
                }
            } else {
                console.error('Failed to toggle like');
            }
        } catch (err) {
            console.error('Error:', err);
        }
    });
});

document.querySelectorAll('.admin-set-likes-btn').forEach(button => {
    button.addEventListener('click', function () {
        const postId = this.dataset.postId;
        const currentLikes = this.dataset.currentLikes;

        document.getElementById('adminLikesInput').value = currentLikes;
        document.getElementById('currentLikesDisplay').textContent = currentLikes;

        document.getElementById('saveAdminLikesBtn').dataset.postId = postId;
    });
});

document.getElementById('saveAdminLikesBtn').addEventListener('click', async function () {
    const postId = this.dataset.postId;
    const newLikes = parseInt(document.getElementById('adminLikesInput').value);

    if (isNaN(newLikes) || newLikes < 0) {
        alert('Please enter a valid number (0 or greater)');
        return;
    }

    try {
        const res = await fetch('/api/admin-set-likes.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                postId: parseInt(postId),
                likes: newLikes
            })
        });

        if (res.ok) {
            const data = await res.json();
            if (data.success) {
                const likeButton = document.querySelector(`button[data-post-id="${postId}"].like-post`);
                const likeCountSpan = likeButton.querySelector('.like-count');

                if (newLikes === 0) {
                    if (likeCountSpan) likeCountSpan.remove();
                } else {
                    if (likeCountSpan) {
                        likeCountSpan.textContent = newLikes;
                    } else {
                        const span = document.createElement('span');
                        span.className = 'like-count ms-1';
                        span.textContent = newLikes;
                        likeButton.appendChild(span);
                    }
                }

                likeButton.dataset.likes = newLikes;

                const modal = bootstrap.Modal.getInstance(document.getElementById('adminSetLikesModal'));
                modal.hide();
            } else {
                alert('Failed to update likes: ' + (data.error || 'Unknown error'));
            }
        } else {
            console.error('Failed to update admin likes');
        }
    } catch (err) {
        console.error('Error:', err);
        alert('Network error during admin likes update');
    }
});