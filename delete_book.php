<?php
include 'db.php';
if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$id = intval($_GET['id']);

// First delete borrow records for this book (since we have no FK)
$stmt = $conn->prepare("DELETE FROM borrow_records WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Then delete the book
$stmt2 = $conn->prepare("DELETE FROM books WHERE book_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();

header("Location: index.php");
exit;
?>
