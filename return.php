<?php
include 'db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$record_id = intval($_GET['id']);

// POST: process return
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get the record to know book_id
    $stmt0 = $conn->prepare("SELECT book_id FROM borrow_records WHERE record_id = ?");
    $stmt0->bind_param("i", $record_id);
    $stmt0->execute();
    $rec = $stmt0->get_result()->fetch_assoc();
    $book_id = $rec['book_id'];

    $ret_date = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE borrow_records SET returned = 1, return_date = ? WHERE record_id = ?");
    $stmt->bind_param("si", $ret_date, $record_id);
    $stmt->execute();

    $stmt2 = $conn->prepare("UPDATE books SET available = 1 WHERE book_id = ?");
    $stmt2->bind_param("i", $book_id);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}

// GET: show info
$stmt = $conn->prepare("SELECT br.*, u.name, b.title FROM borrow_records br LEFT JOIN users u ON br.user_id = u.user_id LEFT JOIN books b ON br.book_id = b.book_id WHERE br.record_id = ?");
$stmt->bind_param("i", $record_id);
$stmt->execute();
$info = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Return</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Return Book</h2>
  <p>Book: <?php echo e($info['title']); ?></p>
  <p>Borrowed by: <?php echo e($info['name']); ?> on <?php echo e($info['borrow_date']); ?></p>

  <form method="POST">
    <button class="button" type="submit">Confirm Return</button>
    <a class="button" href="index.php">Cancel</a>
  </form>
</div>
</body></html>
