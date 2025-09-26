<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year = intval($_POST['year']) ?: NULL;

    $stmt = $conn->prepare("INSERT INTO books (title, author, year_published) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $author, $year);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Book</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Add Book</h2>
  <form method="POST">
    <label>Title</label>
    <input name="title" required>
    <label>Author</label>
    <input name="author" required>
    <label>Year Published</label>
    <input name="year" type="number">
    <button class="button" type="submit">Add</button>
    <a href="index.php" class="button">Cancel</a>
  </form>
</div>
</body>
</html>
