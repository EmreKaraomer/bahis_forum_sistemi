<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Tüm alanları doldurmalısınız.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Şifreler uyuşmuyor.";
    } else {
        // Şifreyi hashle
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Aynı kullanıcı adı veya eposta varsa engelle
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $check->bind_param("ss", $email, $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Bu kullanıcı adı veya e-posta zaten kayıtlı.";
        } else {
            // Kayıt işlemi
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: index.php"); // Giriş sayfasına yönlendir
                exit();
            } else {
                $errors[] = "Kayıt sırasında hata oluştu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Kayıt Ol</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): echo "<div>$e</div>"; endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label>Kullanıcı Adı</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>E-Posta</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Şifre</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Şifre (Tekrar)</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        <a href="index.php" class="btn btn-link">Zaten hesabım var</a>
    </form>
</body>
</html>
