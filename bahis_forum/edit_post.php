<?php
require 'auth.php';
require 'db.php';

$user_id = $_SESSION["user_id"];
$errors = [];

// 1. Gönderi ID’sini al
if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$post_id = (int) $_GET["id"];

// 2. Bu gönderi mevcut mu ve bu kullanıcıya mı ait?
$stmt = $conn->prepare("SELECT title, content, match_date FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: dashboard.php");
    exit();
}

$post = $result->fetch_assoc();

// 3. Güncelleme formu gönderildi mi?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $match_date = $_POST["match_date"];

    if (empty($title) || empty($content)) {
        $errors[] = "Başlık ve içerik boş bırakılamaz.";
    } else {
        $update = $conn->prepare("UPDATE posts SET title = ?, content = ?, match_date = ? WHERE id = ? AND user_id = ?");
        $update->bind_param("sssii", $title, $content, $match_date, $post_id, $user_id);

        if ($update->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Güncelleme sırasında hata oluştu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Analizi Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Bahis Analizi Düzenle</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): echo "<div>$e</div>"; endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Başlık</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Maç Tarihi</label>
            <input type="date" name="match_date" class="form-control" value="<?php echo htmlspecialchars($post['match_date']); ?>">
        </div>
        <div class="mb-3">
            <label>Bahis Analizi</label>
            <textarea name="content" rows="6" class="form-control" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="dashboard.php" class="btn btn-secondary">İptal</a>
    </form>
</body>
</html>
