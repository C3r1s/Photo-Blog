const userId = document.body.dataset.userId;

document.getElementById('saveChangesBtn').addEventListener('click', async () => {
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const newPassword = document.getElementById('newPassword').value;

    const res = await fetch('/api/profile.php?action=update_profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: parseInt(userId),
            username,
            avatar: null,
            email
        })
    });
    const data = await res.json();

    if (data.success) {
        alert('Profile updated!');
        location.reload();
    } else {
        alert('Error: ' + data.error);
    }
});

document.getElementById('changePasswordBtn').addEventListener('click', async () => {
    const oldPassword = prompt("Enter your current password:");
    const newPassword = document.getElementById('newPassword').value;

    if (!oldPassword || !newPassword) {
        alert('Both fields are required.');
        return;
    }

    const res = await fetch('/api/profile.php?action=change_password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ oldPassword, newPassword })
    });
    const data = await res.json();

    if (data.success) {
        alert('Password changed!');
        document.getElementById('newPassword').value = '';
    } else {
        alert('Error: ' + data.error);
    }
});

document.getElementById('changePhotoBtn').addEventListener('click', () => {
    document.getElementById('avatarInput').click();
});

document.getElementById('avatarInput').addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('avatar', file);

    const res = await fetch('/api/upload-avatar.php', {
        method: 'POST',
        body: formData
    });

    const data = await res.json();
    if (data.success) {
        document.getElementById('avatarPreview').src = data.avatarUrl + '?t=' + Date.now();
        alert('Avatar updated!');
    } else {
        alert('Upload failed: ' + data.error);
    }
});