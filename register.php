<?php
require_once __DIR__ . '/../common/db.php';
$message = '';
$isError = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $message = 'Enter a name, valid email, and password with at least 8 characters.';
        $isError = true;
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
        $isError = true;
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $message = 'This email is already registered.';
            $isError = true;
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $stmt->execute([$name,$email,$hash,'student']);
            $message = 'Registration successful. You can now log in.';
        }
    }
}
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 9 - Registration</title><link rel="stylesheet" href="../style.css"></head><body><main class="container narrow">
<nav><a href="../practical8/index.php">Practical 8</a><a href="register.php">Practical 9</a><a href="../practical10/login.php">Practical 10</a></nav>
<h1>Secure User Registration</h1>
<?php if ($message): ?><p class="<?= $isError ? 'error' : 'message' ?>"><?= h($message) ?></p><?php endif; ?>
<form method="post">
<label>Full Name<input name="name" required maxlength="100" value="<?= h($_POST['name'] ?? '') ?>"></label>
<label>Email<input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>"></label>
<label>Password<input type="password" name="password" minlength="8" required></label>
<label>Confirm Password<input type="password" name="confirm_password" minlength="8" required></label>
<button type="submit">Register</button>
</form><p>Already registered? <a href="../practical10/login.php">Login here</a>.</p>
</main></body></html>
