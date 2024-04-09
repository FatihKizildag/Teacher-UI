<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "obs_system";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $conn->connect_error);
}
?>
