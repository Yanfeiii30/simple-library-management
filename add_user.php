<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    header("Location: users.php");
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add User</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Add User</h2>
  <form method="POST">
    <label>Name</label><input name="name" required>
    <label>Email</label><input name="email" type="email">
    <button class="button" type="submit">Add</button>
    <a class="button" href="users.php">Back</a>
  </form>
</div>
</body></html>
