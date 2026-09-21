<?php
    declare(strict_types=1);
    session_start();

    if (!empty($_SESSION['authenticated'])) {
        header('Location: index.php');
        exit;
    }

    $error = $_SESSION['flash'] ?? '';
    unset($_SESSION['flash']);
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $error = 'Enter a valid email address.';
        } elseif (empty($_SESSION['registered_email']) || strcasecmp($_SESSION['registered_email'], $email) !== 0) {
            $error = 'That email is not registered.';
        } elseif (!password_verify($password, (string) $_SESSION['registered_password'])) {
            $error = 'Incorrect password.';
        } else {
            session_regenerate_id(true);
            $_SESSION['authenticated'] = true;
            header('Location: index.php');
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <p id="email-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="post" id="login-form">
        <label>
            Email
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
        </label>
        <br>
        <label>
            Password
            <input type="password" name="password" minlength="8" required>
        </label>
        <br>
        <button type="submit">Log in</button>
    </form>

    <p><a href="register.php">Register</a></p>

    <script>
        document.getElementById('login-form').addEventListener('submit', function (event) {
            const emailInput = document.getElementById('email');
            const error = document.getElementById('email-error');
            const registeredEmail = localStorage.getItem('registeredEmail');

            if (!registeredEmail || emailInput.value.trim().toLowerCase() !== registeredEmail.toLowerCase()) {
                event.preventDefault();
                error.textContent = 'Incorrect email. Use the email from your registered account.';
            }
        });
    </script>
</body>
</html>