<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id = $_GET['id'];
$pdo->prepare("DELETE FROM tags WHERE id = ?")->execute([$id]);
$pdo->prepare("DELETE FROM blog_tag WHERE tag_id = ?")->execute([$id]);

header("Location: ./tag_index.php");


include 'header.php';

exit;
