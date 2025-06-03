<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Удаляем все книги из корзины этого пользователя
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Purchase Complete</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="success-box">
    <h2> Thank you for your purchase!🎉</h2>
    <p>Your order has been placed and your cart is now empty.</p>
    <a href="index.php" class="btn-back">← Back to Store</a>
</div>

</body>
</html>
