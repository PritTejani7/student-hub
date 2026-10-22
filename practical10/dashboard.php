<?php
require_once __DIR__ . '/../common/auth.php';
requireLogin();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Student Dashboard</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="dashboard.php">Dashboard</a><a href="../practical8/index.php">Database</a><a href="logout.php">Logout</a></nav>
<h1>Student Dashboard</h1><p class="message">Login successful.</p>
<p>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Student', ENT_QUOTES, 'UTF-8') ?>.</p>
<p>Your role: <strong><?= htmlspecialchars($_SESSION['role'] ?? 'student', ENT_QUOTES, 'UTF-8') ?></strong></p>
<p>Session timeout: 15 minutes of inactivity.</p>
</main></body></html>
