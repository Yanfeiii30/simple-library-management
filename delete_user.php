<?php
include 'db.php';
if (!isset($_GET['id'])) { header("Location: users.php"); exit; }
$id = intval($_GET['id']);

// remove borrow records of this user (if you want)
$stmt = $conn->prepare("DELETE FROM borrow_records WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// delete user
$stmt2 = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();

header("Location: users.php");
exit;
?>
