<?php
// Display stored JSON records safely
$jsonFile = __DIR__ . "/data.json";
$records = [];

if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
    $json = file_get_contents($jsonFile);
    $records = json_decode($json, true) ?: [];
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stored Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container wide">
    <h1>Stored Student Records</h1>

    <?php if (empty($records)): ?>
        <p>No records found.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Message</th>
            </tr>

            <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= e($record["date"] ?? "") ?></td>
                    <td><?= e($record["name"] ?? "") ?></td>
                    <td><?= e($record["email"] ?? "") ?></td>
                    <td><?= e($record["phone"] ?? "") ?></td>
                    <td><?= e($record["course"] ?? "") ?></td>
                    <td><?= e($record["message"] ?? "") ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p><a href="index.php">Back to Form</a></p>
</div>
</body>
</html>