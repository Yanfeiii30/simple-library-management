<?php
include 'db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q !== '') {
    $like = "%$q%";
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $conn->query("SELECT * FROM books ORDER BY title");
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Library Catalog</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
  <h1>Simple Library Management</h1>
  <nav>
    <a class="button" href="index.php">Catalog</a>
    <a class="button" href="add_book.php">Add Book</a>
    <a class="button" href="users.php">Manage Users</a>
    <a class="button" href="add_user.php">Add User</a>
  </nav>

  <form method="GET" style="margin-top:12px;">
    <input type="search" name="q" placeholder="Search by title or author" value="<?php echo e($q); ?>">
    <button type="submit" class="button">Search</button>
    <?php if($q!==''): ?><a href="index.php" class="button">Clear</a><?php endif; ?>
  </form>

  <table>
    <thead>
      <tr><th>Title</th><th>Author</th><th>Year</th><th>Status</th><th>Actions</th></tr>
    </thead>
    <tbody>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo e($row['title']); ?></td>
        <td><?php echo e($row['author']); ?></td>
        <td><?php echo e($row['year_published']); ?></td>
        <td>
          <?php if ($row['available']): ?>Available<?php else: ?>Borrowed<?php endif; ?>
        </td>
        <td>
          <?php if ($row['available']): ?>
            <a class="button" href="borrow.php?id=<?php echo $row['book_id']; ?>">Borrow</a>
          <?php else: 
            // find active borrow record for this book
            $stmt2 = $conn->prepare("SELECT br.record_id, u.name, br.borrow_date FROM borrow_records br LEFT JOIN users u ON br.user_id = u.user_id WHERE br.book_id = ? AND br.returned = 0 ORDER BY br.borrow_date DESC LIMIT 1");
            $stmt2->bind_param("i", $row['book_id']);
            $stmt2->execute();
            $r2 = $stmt2->get_result();
            if ($r2 && $r2->num_rows):
              $rec = $r2->fetch_assoc();
          ?>
            <span>Borrowed by <?php echo e($rec['name'] ?? 'Unknown'); ?> on <?php echo e($rec['borrow_date']); ?></span>
            <a class="button" href="return.php?id=<?php echo $rec['record_id']; ?>">Return</a>
          <?php else: ?>
            <span>Borrowed</span>
          <?php endif; endif; ?>

          <a class="button" href="edit_book.php?id=<?php echo $row['book_id']; ?>">Edit</a>
          <a class="button" href="delete_book.php?id=<?php echo $row['book_id']; ?>" onclick="return confirm('Delete this book?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
