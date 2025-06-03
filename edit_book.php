<?php
session_start();
require_once '../includes/db.php';

// Проверка на администратора
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Получение книги по ID
if (!isset($_GET['id'])) {
    header("Location: ../admin_dashboard.php");
    exit;
}

$book_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    echo "Book not found.";
    exit;
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $price = floatval($_POST['price']);
    $image = trim($_POST['image']);

    $update = $conn->prepare("UPDATE books SET title = ?, author = ?, price = ?, image = ? WHERE id = ?");
    $update->bind_param("ssdsi", $title, $author, $price, $image, $book_id);
    
    if ($update->execute()) {
        header("Location: ../admin_dashboard.php?updated=1");
        exit;
    } else {
        $error = "Failed to update book.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="edit-container">
    <h2>Edit Book</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br>
        <input type="number" step="0.01" name="price" value="<?= $book['price'] ?>" required><br>
        <input type="text" name="image" value="<?= htmlspecialchars($book['image']) ?>" required><br>
        <button type="submit">Update Book</button>
    </form>

    <div class="form-footer">
        <a href="../admin_dashboard.php">← Back to Dashboard</a>
    </div>
</div>
</body>
</html>
