<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
    $stmt = $pdo->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
    $stmt->execute([$name, $slug]);
    header("Location: ./tag_index.php");
    exit;
}
include 'header.php';

?>

<div class="container mt-5" style="max-width: 600px;">
  <h4 class="mb-3">Create Tag</h4>
  <form method="POST">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" placeholder="Tag name" required>
    </div>
    <button type="submit" class="btn btn-success">Create Tag</button>
    <a href="./tag_index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>
