<?php
require_once __DIR__ . '/../common/db.php';
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
$message = '';
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    try {
        if ($action === 'delete' && $id > 0) {
            $stmt = $pdo->prepare('SELECT poster FROM events WHERE id=?');
            $stmt->execute([$id]); $old = $stmt->fetch();
            $stmt = $pdo->prepare('DELETE FROM events WHERE id=?'); $stmt->execute([$id]);
            if ($old && $old['poster'] && is_file($uploadDir . basename($old['poster']))) { unlink($uploadDir . basename($old['poster'])); }
            $message = 'Event deleted.';
        } elseif ($action === 'save') {
            $title = trim($_POST['title'] ?? '');
            $date = $_POST['event_date'] ?? '';
            $category = trim($_POST['category'] ?? 'General');
            $description = trim($_POST['description'] ?? '');
            $status = ($_POST['status'] ?? 'open') === 'closed' ? 'closed' : 'open';
            if ($title === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                throw new RuntimeException('Enter an event title and valid date.');
            }
            $posterName = null;
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['poster']['error'] !== UPLOAD_ERR_OK || $_FILES['poster']['size'] > 2 * 1024 * 1024) {
                    throw new RuntimeException('Poster must be smaller than 2 MB.');
                }
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['poster']['tmp_name']);
                $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
                if (!isset($allowed[$mime])) { throw new RuntimeException('Only JPG, PNG and WEBP images are allowed.'); }
                $posterName = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
                if (!move_uploaded_file($_FILES['poster']['tmp_name'], $uploadDir . $posterName)) {
                    throw new RuntimeException('Poster upload failed.');
                }
            }
            if ($id > 0) {
                if ($posterName) {
                    $stmt = $pdo->prepare('SELECT poster FROM events WHERE id=?'); $stmt->execute([$id]); $old = $stmt->fetch();
                    $stmt = $pdo->prepare('UPDATE events SET title=?,event_date=?,category=?,description=?,status=?,poster=? WHERE id=?');
                    $stmt->execute([$title,$date,$category,$description,$status,$posterName,$id]);
                    if ($old && $old['poster'] && is_file($uploadDir . basename($old['poster']))) { unlink($uploadDir . basename($old['poster'])); }
                } else {
                    $stmt = $pdo->prepare('UPDATE events SET title=?,event_date=?,category=?,description=?,status=? WHERE id=?');
                    $stmt->execute([$title,$date,$category,$description,$status,$id]);
                }
                $message = 'Event updated.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO events (title,event_date,category,description,status,poster) VALUES (?,?,?,?,?,?)');
                $stmt->execute([$title,$date,$category,$description,$status,$posterName]);
                $message = 'Event added.';
            }
        }
    } catch (Throwable $e) {
        $message = $e instanceof RuntimeException ? $e->getMessage() : 'Database operation failed.';
    }
}
$edit = null;
if (isset($_GET['edit'])) { $stmt=$pdo->prepare('SELECT * FROM events WHERE id=?'); $stmt->execute([(int)$_GET['edit']]); $edit=$stmt->fetch(); }
$filter = ($_GET['status'] ?? '');
$sql = 'SELECT * FROM events'; $params = [];
if (in_array($filter, ['open','closed'], true)) { $sql .= ' WHERE status=?'; $params[]=$filter; }
$sql .= ' ORDER BY event_date';
$stmt=$pdo->prepare($sql); $stmt->execute($params); $events=$stmt->fetchAll();
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 12 - Event CRUD</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="../practical11/index.php">Practical 11</a><a href="../practical12/index.php">Practical 12</a><a href="../practical13/index.php">Practical 13</a><a href="../practical14/index.php">Practical 14</a></nav>
<h1>Practical 12: Event Management CRUD</h1><p class="muted">Create, edit, delete events and upload validated posters (JPG, PNG, WEBP; max 2 MB).</p>
<?php if ($message): ?><p class="<?= str_contains(strtolower($message),'failed') || str_contains(strtolower($message),'must') || str_contains(strtolower($message),'only') || str_contains(strtolower($message),'enter') ? 'error':'message' ?>"><?= h($message) ?></p><?php endif; ?>
<h2><?= $edit ? 'Edit Event' : 'Add Event' ?></h2><form method="post" enctype="multipart/form-data"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= h($edit['id'] ?? 0) ?>">
<label>Event Title<input name="title" required value="<?= h($edit['title'] ?? '') ?>"></label>
<label>Date<input type="date" name="event_date" required value="<?= h($edit['event_date'] ?? '') ?>"></label>
<label>Category<input name="category" value="<?= h($edit['category'] ?? 'General') ?>"></label>
<label>Description<textarea name="description"><?= h($edit['description'] ?? '') ?></textarea></label>
<label>Status<select name="status"><option value="open" <?= ($edit['status'] ?? 'open') === 'open' ? 'selected' : '' ?>>Open</option><option value="closed" <?= ($edit['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option></select></label>
<label>Poster (optional)<input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp"></label>
<button>Save Event</button> <?php if ($edit): ?><a href="index.php">Cancel edit</a><?php endif; ?></form>
<h2>Events</h2><form method="get"><label>Filter status<select name="status"><option value="">All</option><option value="open" <?= $filter==='open'?'selected':'' ?>>Open</option><option value="closed" <?= $filter==='closed'?'selected':'' ?>>Closed</option></select></label><button>Filter</button></form>
<table><thead><tr><th>Title</th><th>Date</th><th>Category</th><th>Status</th><th>Poster</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($events as $e): ?><tr><td><?= h($e['title']) ?><br><span class="muted"><?= h($e['description']) ?></span></td><td><?= h($e['event_date']) ?></td><td><?= h($e['category']) ?></td><td><?= h($e['status']) ?></td><td><?php if ($e['poster']): ?><a href="uploads/<?= rawurlencode(basename($e['poster'])) ?>" target="_blank">View poster</a><?php else: ?>—<?php endif; ?></td><td><a href="?edit=<?= h($e['id']) ?>">Edit</a><form class="inline" method="post" onsubmit="return confirm('Delete this event?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= h($e['id']) ?>"><button class="danger">Delete</button></form></td></tr><?php endforeach; ?>
<?php if (!$events): ?><tr><td colspan="6">No events yet.</td></tr><?php endif; ?></tbody></table></main></body></html>
