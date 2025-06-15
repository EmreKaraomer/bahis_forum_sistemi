<?php
$host = "localhost"; // veya hosting ortamına göre değiştirin
$user = "root";      // XAMPP/MAMP kullanıyorsan root
$pass = "";          // genelde boş
$dbname = "bahis_forum"; // veritabanı adın

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>
