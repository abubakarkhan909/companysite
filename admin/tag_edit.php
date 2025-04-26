<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id = $_GET['id'];
$tag = $pdo->prepare("SELECT * FROM tags WHERE id = ?");
$tag->execute([$id]);
$tag = $tag->fetch();

if (!$tag) {
    echo "<div class='alert alert-danger'>Tag not found.</div>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name));
    $stmt = $pdo->prepare("UPDATE tags SET name = ?, slug = ? WHERE id = ?");
    $stmt->execute([$name, $slug, $id]);
    header("Location: ./tag_index.php");
    exit;
}
include 'header.php';

?>

<div class="container mt-5" style="max-width: 600px;">
  <h4 class="mb-3">Edit Tag</h4>
  <form method="POST">
    <div class="mb-3">
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($tag['name']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Tag</button>
    <a href="./tag_index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include 'footer.php'; ?>
