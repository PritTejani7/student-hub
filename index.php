<?php
require_once __DIR__ . '/../common/db.php';
$students = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$events = $pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 8 - Database Connection</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="index.php">Practical 8</a><a href="../practical9/register.php">Practical 9</a><a href="../practical10/login.php">Practical 10</a></nav>
<h1>Practical 8: MySQL Schema and PDO Connectivity</h1><p class="message">Database connected successfully using PDO.</p>
<div class="card">Students table records: <strong><?= (int)$students ?></strong></div>
<div class="card">Events table records: <strong><?= (int)$events ?></strong></div>
<h2>Tables created</h2><p>students, events, registrations and users.</p>
<p>This page uses a prepared connection file at <code>common/db.php</code>.</p>
</main></body></html>
