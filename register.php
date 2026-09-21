<?php
    declare(strict_types=1);
    session_start();

    if (!empty($_SESSION['authenticated'])) {
        header('Location: index.php');
        exit;
    }

    $errors = [];
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Enter a valid email address.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($errors === []) {
            $_SESSION['registered_email'] = $email;
            $_SESSION['registered_password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['flash'] = 'Registration successful. You can now log in.';
            header('Location: login.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    <?php foreach ($errors as $error): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <form method="post">
        <label>
            Email
            <input type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
        </label>
        <br>
        <label>
            Password
            <input type="password" name="password" minlength="8" required>
        </label>
        <br>
        <button type="submit">Register</button>
    </form>

    <p><a href="login.php">Log in</a></p>

    <script>
        document.querySelector('form').addEventListener('submit', function () {
            localStorage.setItem('registeredEmail', document.querySelector('[name="email"]').value.trim());
        });
    </script>
</body>
</html>