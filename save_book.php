<?php
require_once '../includes/db.php';
session_start();

// Только админ имеет доступ
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Проверка на заполненность
if (!isset($_POST['title'], $_POST['author'], $_POST['price'], $_POST['description']) || !isset($_FILES['cover'])) {
    die("All fields are required.");
}

// Получаем данные
$title = $conn->real_escape_string($_POST['title']);
$author = $conn->real_escape_string($_POST['author']);
$price = (float) $_POST['price'];
$description = $conn->real_escape_string($_POST['description']);
$genre = $conn->real_escape_string($_POST['genre']);


// Папка для загрузки
$uploadDir = '../assets/img/';
$filename = basename($_FILES['cover']['name']);
$targetPath = $uploadDir . $filename;
$imagePath = 'assets/img/' . $filename; // вот это будет записано в БД

// Перемещаем файл
if (!move_uploaded_file($_FILES['cover']['tmp_name'], $targetPath)) {
    die("Failed to upload image.");
}

// Сохраняем в базу
$sql = "INSERT INTO books (title, author, description, price, image, genre)
        VALUES ('$title', '$author', '$description', '$price', '$imagePath', '$genre')";


if ($conn->query($sql) === TRUE) {
    header("Location: ../admin_dashboard.php?success=1");
    exit;
} else {
    die("Database error: " . $conn->error);
}
?>
