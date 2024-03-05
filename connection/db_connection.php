<?php
// Veritabanı bilgilerini değiştirin
$servername = "localhost";
$username = "root";
$password = "";
$database = "your_database_name";

// Veritabanı bağlantısını oluşturun
$conn = new mysqli($servername, $username, $password, $database);

// Bağlantıyı kontrol edin
if ($conn->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $conn->connect_error);
}
?>
