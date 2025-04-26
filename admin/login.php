<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ./dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<?php include 'header.php'; ?>

<div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
  <h4 class="mb-3">Login</h4>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>
    <button class="btn btn-primary w-100" type="submit">Login</button>
  </form>
  <div class="mt-3 text-center">
    <a href="register.php">Register</a> | <a href="reset_password.php">Forgot Password?</a>
  </div>
</div>

<?php include 'footer.php'; ?>
