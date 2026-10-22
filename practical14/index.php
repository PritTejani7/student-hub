<?php
require_once __DIR__ . '/../common/db.php';
require_once __DIR__ . '/../common/auth.php';
requireAdmin();
$students = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$events = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$users = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$registrations = (int)$pdo->query('SELECT COUNT(*) FROM registrations')->fetchColumn();
$latest = $pdo->query('SELECT id,name,email,course,created_at FROM students ORDER BY id DESC LIMIT 5')->fetchAll();
$logs = $pdo->query('SELECT a.action,a.created_at,u.name FROM audit_logs a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 8')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log_action'])) {
    logAction($pdo, 'Admin dashboard action: ' . trim($_POST['log_action']));
    header('Location: index.php?logged=1');
    exit;
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 14 - Admin Dashboard</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="index.php">Dashboard</a><a href="../practical11/index.php">Students</a><a href="../practical12/index.php">Events</a><a href="../practical13/index.php">Fetch API CRUD</a><a href="../logout.php">Logout</a></nav>
<h1>Admin Dashboard</h1><p>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?>. Admin-only page.</p>
<?php if (isset($_GET['logged'])): ?><p class="message">Audit log entry saved.</p><?php endif; ?>
<div class="grid"><div class="stat">Students<strong><?= $students ?></strong></div><div class="stat">Events<strong><?= $events ?></strong></div><div class="stat">Users<strong><?= $users ?></strong></div><div class="stat">Registrations<strong><?= $registrations ?></strong></div></div>
<h2>Dynamic Menu</h2><p>Your role is <strong><?= htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') ?></strong>. This menu is shown only after admin access is verified.</p>
<h2>Record an Audit Action</h2><form method="post"><label>Action description<input name="log_action" required maxlength="200" placeholder="Example: Reviewed event list"></label><button>Save Audit Log</button></form>
<h2>Latest Student Records</h2><table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Created</th></tr></thead><tbody><?php foreach ($latest as $s): ?><tr><td><?= (int)$s['id'] ?></td><td><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($s['email'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($s['course'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($s['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endforeach; ?></tbody></table>
<h2>Recent Audit Logs</h2><table><thead><tr><th>Action</th><th>User</th><th>Time</th></tr></thead><tbody><?php foreach ($logs as $log): ?><tr><td><?= htmlspecialchars($log['action'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($log['name'] ?? 'System', ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($log['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endforeach; ?><?php if (!$logs): ?><tr><td colspan="3">No audit entries yet.</td></tr><?php endif; ?></tbody></table>
</main></body></html>
