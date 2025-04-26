<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $fullname = trim($_POST['fullname']);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->fetch()) {
        $message = "Username already taken.";
        $type = "danger";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (fullname, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullname, $username, $password]);
        $message = "Registration successful. <a href='./login.php'>Login</a>";
        $type = "success";
    }
}

include 'header.php';

?>

<div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
  <h4 class="mb-3">Register</h4>
  <?php if (!empty($message)): ?>
    <div class="alert alert-<?= $type ?>"><?= $message ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
    </div>
    <div class="mb-3">
      <input type="text" class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="mb-3">
      <input type="password" class="form-control" name="password" placeholder="Password" required>
    </div>
    <button class="btn btn-success w-100" type="submit">Register</button>
  </form>
</div>

<?php include 'footer.php'; ?>
