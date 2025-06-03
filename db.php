<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bookstore"; // ← это должно совпадать с базой в phpMyAdmin

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
