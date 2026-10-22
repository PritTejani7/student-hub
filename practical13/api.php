<?php
require_once __DIR__ . '/../common/db.php';
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
function respond($data, int $status=200): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $stmt = $pdo->query('SELECT id,title,event_date,category,description,status FROM events ORDER BY event_date');
    respond(['ok'=>true,'events'=>$stmt->fetchAll()]);
}
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) { $input = $_POST; }
$action = $input['action'] ?? '';
if ($method === 'POST' && $action === 'add') {
    $title = trim($input['title'] ?? '');
    $date = $input['event_date'] ?? '';
    $category = trim($input['category'] ?? 'General');
    if ($title === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        respond(['ok'=>false,'message'=>'Title and valid date are required.'], 422);
    }
    $stmt = $pdo->prepare('INSERT INTO events (title,event_date,category) VALUES (?,?,?)');
    $stmt->execute([$title,$date,$category]);
    respond(['ok'=>true,'message'=>'Event added.','id'=>(int)$pdo->lastInsertId()], 201);
}
if ($method === 'POST' && $action === 'update') {
    $id = (int)($input['id'] ?? 0);
    $title = trim($input['title'] ?? '');
    $date = $input['event_date'] ?? '';
    $category = trim($input['category'] ?? 'General');
    $status = ($input['status'] ?? 'open') === 'closed' ? 'closed' : 'open';
    if ($id < 1 || $title === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        respond(['ok'=>false,'message'=>'ID, title and valid date are required.'], 422);
    }
    $stmt = $pdo->prepare('UPDATE events SET title=?,event_date=?,category=?,status=? WHERE id=?');
    $stmt->execute([$title,$date,$category,$status,$id]);
    respond(['ok'=>true,'message'=>$stmt->rowCount() ? 'Event updated.' : 'No changes made.']);
}
if ($method === 'POST' && $action === 'delete') {
    $id = (int)($input['id'] ?? 0);
    if ($id < 1) { respond(['ok'=>false,'message'=>'Valid ID required.'], 422); }
    $stmt = $pdo->prepare('DELETE FROM events WHERE id=?');
    $stmt->execute([$id]);
    respond(['ok'=>true,'message'=>$stmt->rowCount() ? 'Event deleted.' : 'Event not found.']);
}
respond(['ok'=>false,'message'=>'Unsupported method or action.'], 405);
