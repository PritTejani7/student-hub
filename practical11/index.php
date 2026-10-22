<?php
require_once __DIR__ . '/../common/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';
    try {
        if ($action === 'save' && $name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && $course !== '') {
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE students SET name=?, email=?, course=?, status=? WHERE id=?');
                $stmt->execute([$name,$email,$course,$status,$id]);
                $message = 'Student updated successfully.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO students (name,email,course,status) VALUES (?,?,?,?)');
                $stmt->execute([$name,$email,$course,$status]);
                $message = 'Student added successfully.';
            }
        } elseif ($action === 'delete' && $id > 0) {
            $stmt = $pdo->prepare('DELETE FROM students WHERE id=?');
            $stmt->execute([$id]);
            $message = 'Student deleted.';
        } else {
            $message = 'Please enter a valid name, email, and course.';
        }
    } catch (PDOException $e) {
        $message = 'Could not save. The email may already exist.';
    }
}
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}
$q = trim($_GET['q'] ?? '');
$courseFilter = trim($_GET['course'] ?? '');
$sql = 'SELECT * FROM students WHERE (name LIKE ? OR email LIKE ? OR course LIKE ?)';
$params = ["%$q%","%$q%","%$q%"];
if ($courseFilter !== '') { $sql .= ' AND course=?'; $params[] = $courseFilter; }
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();
$courses = $pdo->query('SELECT DISTINCT course FROM students ORDER BY course')->fetchAll(PDO::FETCH_COLUMN);
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Practical 11 - Student CRUD</title><link rel="stylesheet" href="../style.css"></head><body><main class="container">
<nav><a href="../practical11/index.php">Practical 11</a><a href="../practical12/index.php">Practical 12</a><a href="../practical13/index.php">Practical 13</a><a href="../practical14/index.php">Practical 14</a></nav>
<h1>Practical 11: Student Management CRUD</h1><p class="muted">Add, view, edit, delete, search and filter student records.</p>
<?php if ($message): ?><p class="message"><?= h($message) ?></p><?php endif; ?>
<h2><?= $edit ? 'Edit Student' : 'Add Student' ?></h2>
<form method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= h($edit['id'] ?? 0) ?>">
<label>Student Name<input name="name" required value="<?= h($edit['name'] ?? '') ?>"></label>
<label>Email<input type="email" name="email" required value="<?= h($edit['email'] ?? '') ?>"></label>
<label>Course<input name="course" required value="<?= h($edit['course'] ?? '') ?>"></label>
<label>Status<select name="status"><option value="active" <?= ($edit['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option><option value="inactive" <?= ($edit['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option></select></label>
<button type="submit">Save Student</button> <?php if ($edit): ?><a href="index.php">Cancel edit</a><?php endif; ?></form>
<h2>Search and Filter</h2><form method="get"><label>Search name, email or course<input name="q" value="<?= h($q) ?>"></label><label>Course<select name="course"><option value="">All courses</option><?php foreach ($courses as $c): ?><option value="<?= h($c) ?>" <?= $courseFilter === $c ? 'selected' : '' ?>><?= h($c) ?></option><?php endforeach; ?></select></label><button>Search</button> <a href="index.php">Clear</a></form>
<h2>Students (<?= count($students) ?>)</h2><table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($students as $s): ?><tr><td><?= h($s['id']) ?></td><td><?= h($s['name']) ?></td><td><?= h($s['email']) ?></td><td><?= h($s['course']) ?></td><td><?= h($s['status']) ?></td><td><a href="?edit=<?= h($s['id']) ?>">Edit</a><form class="inline" method="post" onsubmit="return confirm('Delete this student?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= h($s['id']) ?>"><button class="danger">Delete</button></form></td></tr><?php endforeach; ?>
<?php if (!$students): ?><tr><td colspan="6">No students found.</td></tr><?php endif; ?></tbody></table>
</main></body></html>
