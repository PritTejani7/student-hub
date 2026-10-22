<?php
require_once __DIR__ . '/../common/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$message = '';
if (isset($_GET['timeout'])) { $message = 'Your session expired. Please log in again.'; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id,name,email,password,role FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: dashboard.php');
        }
        exit;
    }
    $error = 'Incorrect email or password.';
}
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 10 - Login</title><link rel="stylesheet" href="../style.css"></head><body><main class="container narrow">
<nav><a href="../practical8/index.php">Practical 8</a><a href="../practical9/register.php">Practical 9</a><a href="login.php">Practical 10</a></nav>
<h1>Secure Login</h1>
<?php if ($message): ?><p class="error"><?= h($message) ?></p><?php endif; ?>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form method="post"><label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" required></label><button>Login</button></form>
<p>New user? <a href="../practical9/register.php">Register first</a>.</p>
</main></body></html>
