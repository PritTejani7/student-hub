<?php
// Practical 7: PHP Form Processing with Server-Side Validation

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Invalid request.");
}

// Read input
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$course = trim($_POST["course"] ?? "");
$message = trim($_POST["message"] ?? "");

// Server-side validation
$errors = [];

if ($name === "" || !preg_match("/^[a-zA-Z ]{2,50}$/", $name)) {
    $errors[] = "Please enter a valid name.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email.";
}

if (!preg_match("/^[0-9]{10}$/", $phone)) {
    $errors[] = "Phone number must contain exactly 10 digits.";
}

$allowedCourses = ["BCA", "B.Tech", "MCA", "M.Tech"];
if (!in_array($course, $allowedCourses, true)) {
    $errors[] = "Please select a valid course.";
}

// Sanitize data before storage/output
$name = htmlspecialchars($name, ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
$phone = htmlspecialchars($phone, ENT_QUOTES, "UTF-8");
$course = htmlspecialchars($course, ENT_QUOTES, "UTF-8");
$message = htmlspecialchars($message, ENT_QUOTES, "UTF-8");

if (!empty($errors)) {
    echo "<h2>Form has errors</h2>";
    foreach ($errors as $error) {
        echo "<p>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</p>";
    }
    echo '<p><a href="index.php">Go Back</a></p>';
    exit;
}

// Store record
$record = [
    date("Y-m-d H:i:s"),
    $name,
    $email,
    $phone,
    $course,
    $message
];

$csvFile = __DIR__ . "/data.csv";
$jsonFile = __DIR__ . "/data.json";

// CSV storage with file locking
$fp = fopen($csvFile, "a");
if ($fp === false) {
    exit("Unable to open CSV file.");
}

if (flock($fp, LOCK_EX)) {
    fputcsv($fp, $record);
    fflush($fp);
    flock($fp, LOCK_UN);
}
fclose($fp);

// JSON storage
$data = [];

if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
    $json = file_get_contents($jsonFile);
    $decoded = json_decode($json, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}

$data[] = [
    "date" => $record[0],
    "name" => $name,
    "email" => $email,
    "phone" => $phone,
    "course" => $course,
    "message" => $message
];

$jsonResult = file_put_contents(
    $jsonFile,
    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

if ($jsonResult === false) {
    exit("Record saved in CSV, but JSON storage failed.");
}

echo "<!DOCTYPE html>";
echo "<html><head><title>Success</title><link rel='stylesheet' href='style.css'></head><body>";
echo "<div class='container'>";
echo "<h2>Registration Successful!</h2>";
echo "<p>Your data has been validated and stored successfully.</p>";
echo "<p><a href='index.php'>Add Another Record</a></p>";
echo "<p><a href='records.php'>View Stored Records</a></p>";
echo "</div></body></html>";
?>