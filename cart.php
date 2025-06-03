<?php
session_start();
include 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Get books from cart
$sql = "SELECT c.id, b.title, b.author, b.price, c.quantity 
        FROM cart c
        JOIN books b ON c.book_id = b.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <h2 class="cart-title">Your Shopping Cart</h2>
    <?php if (isset($_GET['removed'])): ?>
        <p style="text-align:center; color: green;">✅ Item removed from cart.</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="text-align:center; color: red;">❌ Something went wrong: <?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table class="cart-table">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Quantity</th>
                <th>Price (each)</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php 
            $grandTotal = 0;
            while ($row = $result->fetch_assoc()): 
                $total = $row['price'] * $row['quantity'];
                $grandTotal += $total;
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['price'], 2) ?> USD</td>
                    <td><?= number_format($total, 2) ?> USD</td>
                    <td>
                        <form method="post" action="remove_from_cart.php" onsubmit="return confirm('Are you sure you want to remove this book?');">
                            <input type="hidden" name="cart_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn-delete">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

            <tr>
                <td colspan="4"><strong>Grand Total:</strong></td>
                <td><strong><?= number_format($grandTotal, 2) ?> USD</strong></td>
                <td></td>
            </tr>
        </table>

        <div class="cart-actions">
            <form action="purchase.php" method="POST" style="display:inline;">
                <button type="submit" class="btn-purchase">Purchase</button>
            </form>
            <a href="index.php" class="btn-continue">← Continue shopping</a>
        </div>
    <?php else: ?>
        <p class="cart-empty">Your cart is empty.</p>
        <div class="cart-actions">
            <a href="index.php" class="btn-continue">← Continue shopping</a>
        </div>
    <?php endif; ?>

</body>
</html>
