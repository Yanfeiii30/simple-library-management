<?php
include 'db.php';

if (!isset($_GET['id'])) { header("Location: index.php"); exit; }
$book_id = intval($_GET['id']);

// POST: process borrow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $borrow_date = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO borrow_records (user_id, book_id, borrow_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $book_id, $borrow_date);
    $stmt->execute();

    $stmt2 = $conn->prepare("UPDATE books SET available = 0 WHERE book_id = ?");
    $stmt2->bind_param("i", $book_id);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}

// GET: show form
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

$users = $conn->query("SELECT * FROM users ORDER BY name");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Borrow</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Borrow: <?php echo e($book['title']); ?></h2>
  <?php if ($book['available'] == 0): ?>
    <p>This book is currently not available.</p>
    <a class="button" href="index.php">Back</a>
  <?php else: ?>
    <?php if ($users->num_rows == 0): ?>
      <p>No users found. <a href="add_user.php">Add user first</a></p>
    <?php else: ?>
      <form method="POST">
        <label>Select User</label>
        <select name="user_id" required>
          <?php while($u = $users->fetch_assoc()): ?>
            <option value="<?php echo $u['user_id']; ?>"><?php echo e($u['name']); ?> (<?php echo e($u['email']); ?>)</option>
          <?php endwhile; ?>
        </select>
        <button class="button" type="submit">Confirm Borrow</button>
        <a class="button" href="index.php">Cancel</a>
      </form>
    <?php endif; ?>
  <?php endif; ?>
</div>
</body></html>
