<?php
include 'db.php';
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year = intval($_POST['year']) ?: NULL;
    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, year_published=? WHERE book_id=?");
    $stmt->bind_param("ssii", $title, $author, $year, $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { echo "Book not found"; exit; }
$book = $res->fetch_assoc();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Book</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Edit Book</h2>
  <form method="POST">
    <label>Title</label>
    <input name="title" required value="<?php echo e($book['title']); ?>">
    <label>Author</label>
    <input name="author" required value="<?php echo e($book['author']); ?>">
    <label>Year Published</label>
    <input name="year" type="number" value="<?php echo e($book['year_published']); ?>">
    <button class="button" type="submit">Save</button>
    <a class="button" href="index.php">Cancel</a>
  </form>
</div>
</body></html>
