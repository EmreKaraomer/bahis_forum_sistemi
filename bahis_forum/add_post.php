<?php
require 'auth.php';
require 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $match_date = $_POST["match_date"];

    if (empty($title) || empty($content)) {
        $errors[] = "Başlık ve içerik boş bırakılamaz.";
    } else {
        $user_id = $_SESSION["user_id"];
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, match_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $content, $match_date);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Kayıt sırasında hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bahis Analizi Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Yeni Bahis Analizi Ekle</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): echo "<div>$e</div>"; endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_post.php">
        <div class="mb-3">
            <label>Başlık</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Maç Tarihi (opsiyonel)</label>
            <input type="date" name="match_date" class="form-control">
        </div>
        <div class="mb-3">
            <label>Bahis Analizi</label>
            <textarea name="content" rows="6" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Paylaş</button>
        <a href="dashboard.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</body>
</html>
