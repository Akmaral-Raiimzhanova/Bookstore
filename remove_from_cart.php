<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cart_id"])) {
    $cartId = intval($_POST["cart_id"]);
    $userId = $_SESSION["user_id"];

    // Удаляем только если этот товар принадлежит пользователю
    $delete = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $cartId, $userId);

    if ($delete->execute()) {
        header("Location: cart.php?removed=1");
        exit;
    } else {
        header("Location: cart.php?error=delete_failed");
        exit;
    }
} else {
    header("Location: cart.php?error=invalid");
    exit;
}
?>

