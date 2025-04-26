<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM blog_categories WHERE id = ?");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) {
    echo "<div class='alert alert-danger'>Blog Category not found.</div>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE blog_categories SET name = ? WHERE id = ?");
    $stmt->execute([$_POST['name'], $id]);
    header("Location: ./category_index.php");
    exit;
}
include 'header.php';
?>

<div class="container my-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h4 class="card-title mb-4">Edit Blog Category</h4>
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($blog['name']) ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="./category_index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
