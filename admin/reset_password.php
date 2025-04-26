<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([
        password_hash($_POST['new_password'], PASSWORD_BCRYPT),
        $_POST['username']
    ]);

    if ($stmt->rowCount()) {
        $message = "Password updated successfully. <a href='login.php'>Login</a>";
        $type = "success";
    } else {
        $message = "User not found.";
        $type = "danger";
    }
}

include 'header.php';

?>

<div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
  <h4 class="mb-3">Reset Password</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-<?= $type ?>"><?= $message ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
    </div>
    <button class="btn btn-warning w-100" type="submit">Reset Password</button>
  </form>
</div>

<?php include 'footer.php'; ?>
