<?php
session_start();
include 'includes/db.php';

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo "not_logged_in";
    exit;
}

// Check if book_id is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book_id"])) {
    $bookId = intval($_POST["book_id"]);
    $userId = $_SESSION["user_id"];

    // Check if book is already in the cart
    $check = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND book_id = ?");
    $check->bind_param("ii", $userId, $bookId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // If exists, increase quantity by 1
        $check->bind_result($cartId, $quantity);
        $check->fetch();
        $quantity++;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $cartId);
        $update->execute();
        echo "updated";
    } else {
        // If not exists, insert new row
        $insert = $conn->prepare("INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
        $insert->bind_param("ii", $userId, $bookId);
        $insert->execute();
        echo "added";
    }
} else {
    echo "invalid_request";
}
?>
