<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $stmt = $pdo->prepare("INSERT INTO blog_categories (name, slug) VALUES (?, ?)");
    $stmt->execute([$name, $slug]);
    header("Location: ./category_index.php");
    exit;
}
include 'header.php';
?>
<div class="container mt-5">
    <h4>Create Blog Category</h4>
    <form method="POST">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Category Name" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="./category_index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php include 'footer.php'; ?>
