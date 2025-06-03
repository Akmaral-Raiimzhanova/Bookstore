<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $bookId = intval($_POST['book_id']);

    // Шаг 1: удалим книгу из cart
    $stmt_cart = $conn->prepare("DELETE FROM cart WHERE book_id = ?");
    $stmt_cart->bind_param("i", $bookId);
    $stmt_cart->execute();
    $stmt_cart->close();

    // Шаг 2: удалим книгу из books
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $bookId);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../admin_dashboard.php?deleted=1");
        exit;
    } else {
        die("❌ Failed to delete book: " . $stmt->error);
    }
} else {
    die("⚠️ Invalid request.");
}
