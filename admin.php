<?php
require_once __DIR__ . '/../common/auth.php';
requireAdmin();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="admin.php">Admin Dashboard</a><a href="../practical8/index.php">Database</a><a href="logout.php">Logout</a></nav>
<h1>Admin Dashboard</h1><p class="message">Admin role verified. Only admin users can access this page.</p>
<p>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?>.</p>
</main></body></html>
