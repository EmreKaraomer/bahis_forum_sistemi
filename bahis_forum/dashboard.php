<?php
require 'auth.php';
require 'db.php';

// Kullanıcının analizlerini çek
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT id, title, match_date, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Panel - Bahis Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Hoş geldin, <?php echo htmlspecialchars($_SESSION["username"]); ?> 👋</h2>
    
    <div class="mb-3">
        <a href="add_post.php" class="btn btn-primary">Yeni Bahis Analizi Ekle</a>
        <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
        <a href="logout.php" class="btn btn-danger">Çıkış Yap</a>
    </div>

    <h4>Paylaşımların</h4>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Başlık</th>
                    <th>Maç Tarihi</th>
                    <th>Oluşturulma</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["title"]); ?></td>
                        <td><?php echo htmlspecialchars($row["match_date"]); ?></td>
                        <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                        <td>
                            <a href="edit_post.php?id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                            <a href="delete_post.php?id=<?php echo $row["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Henüz hiçbir analiz eklemediniz.</p>
    <?php endif; ?>
</body>
</html>

