<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Простая валидация
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:5262/users');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'avatar' => null,
            'followers' => 0
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed. Email or username may be taken.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Photo Blog</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; }
        body { display: flex; align-items: center; padding-top: 40px; padding-bottom: 40px; background-color: #f8f9fa; }
        .form-signin { max-width: 380px; padding: 20px; margin: auto; }
        .form-signin .form-floating:focus-within { z-index: 2; }
        .form-signin input { margin-bottom: 10px; }
    </style>
</head>
<body class="text-center">

    <main class="form-signin">
        <form method="POST">
            <h1 class="h3 mb-3 fw-normal">Create an account</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="form-floating">
                <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required minlength="6">
                <label for="password">Password (min 6 chars)</label>
            </div>

            <button class="w-100 btn btn-lg btn-success" type="submit">Register</button>

            <p class="mt-3 mb-0">
                Already have an account? <a href="login.php">Sign in</a>
            </p>
        </form>
    </main>

</body>
</html>