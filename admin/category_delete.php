<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM blog_categories WHERE id = ? ");
$stmt->execute([$id]);

header("Location: ./category_index.php");
exit;
