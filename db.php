<?php
$servername = "3.84.5.188";
$username = "root"; 
$password = "root"; 
$database = "db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
