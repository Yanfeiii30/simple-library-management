<?php
// db.php
$host = "db";
$user = "root";
$pass = "rootpassword";
$db   = "library_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// escape safe output
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES);
}
?>
