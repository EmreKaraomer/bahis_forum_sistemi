<?php
session_start();
require 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $errors[] = "Lütfen e-posta ve şifrenizi girin.";
    } else {
        // Kullanıcıyı e-posta ile sorgula
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Giriş başarılı → oturumu başlat
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;

                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = "Şifre hatalı.";
            }
        } else {
            $errors[] = "Böyle bir kullanıcı bulunamadı.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Giriş Yap</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $e): echo "<div>$e</div>"; endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div class="mb-3">
            <label>E-Posta</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Şifre</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Giriş Yap</button>
        <a href="register.php" class="btn btn-link">Hesabın yok mu? Kayıt ol</a>
    </form>
</body>
</html>
