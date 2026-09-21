<?php
    declare(strict_types=1);

    session_start();

    if (empty($_SESSION['authenticated'])) {
        $_SESSION['flash'] = 'Please log in to continue.';
        header('Location: login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        $_SESSION = [];
        session_destroy();
        header('Location: login.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Welcome</h1>
    <p>You are authenticated.</p>
    <form method="post">
        <button type="submit" name="logout">Log out</button>
    </form>
</body>
</html>