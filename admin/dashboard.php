<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM blogs WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$blogs = $stmt->fetchAll();

include 'header.php';

?>

<div class="container-fluid">
  <div class="row">
    
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 bg-light p-4 border-end min-vh-100">
      <h5 class="mb-4">Dashboard</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link active fw-bold" href="./dashboard.php">📝 My Blogs</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link" href="./category_index.php">📂 Blog Categories</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link" href="./tag_index.php">📂 Tags</a>
        </li>
        <li class="nav-item mt-4">
          <a class="btn btn-outline-danger w-100" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>

    <!-- Main content -->
    <div class="col-md-9 col-lg-10 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Your Blogs</h2>
        <a href="./create.php" class="btn btn-primary">+ New Blog</a>
      </div>

      <?php if (count($blogs) === 0): ?>
          <div class="alert alert-info">You haven't created any blogs yet.</div>
      <?php else: ?>
          <div class="list-group">
              <?php foreach ($blogs as $blog): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                          <strong><?= htmlspecialchars($blog['title']) ?></strong><br>
                          <small class="text-muted"><?= date('M d, Y H:i', strtotime($blog['created_at'])) ?></small>
                      </div>
                      <div>
                          <a href="./edit.php?id=<?= $blog['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                          <a href="./delete.php?id=<?= $blog['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                      </div>
                  </div>
              <?php endforeach; ?>
          </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
