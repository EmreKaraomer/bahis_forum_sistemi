<?php
require 'auth.php';
require 'db.php';

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = (int) $_GET["id"];

// Sadece kullanıcıya ait olan gönderiyi sil
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();

// Silme tamamlandıktan sonra geri yönlendir
header("Location: dashboard.php");
exit();
?>
