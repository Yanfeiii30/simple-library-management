<?php
include 'db.php';
$res = $conn->query("SELECT * FROM users ORDER BY name");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Users</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<div class="container">
  <h2>Users</h2>
  <a class="button" href="add_user.php">Add User</a>
  <table>
    <thead><tr><th>Name</th><th>Email</th><th>Action</th></tr></thead>
    <tbody>
      <?php while($u = $res->fetch_assoc()): ?>
      <tr>
        <td><?php echo e($u['name']); ?></td>
        <td><?php echo e($u['email']); ?></td>
        <td>
          <a class="button" href="delete_user.php?id=<?php echo $u['user_id']; ?>" onclick="return confirm('Delete user?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <a href="index.php" class="button">Back to Catalog</a>
</div>
</body>
</html>
