<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

// Загружаем книги из базы
$books = [];
$result = $conn->query("SELECT * FROM books");
if ($result && $result->num_rows > 0) {
    $books = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

  <!-- Кнопка возврата на главную -->
  <div class="back-container">
    <a href="index.php" class="btn-back">← Back to Bookstore</a>
  </div>

  <div class="admin-dashboard">
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
      <p style="color: green;">✅ Book deleted successfully!</p>
    <?php elseif (isset($_GET['deleted']) && $_GET['deleted'] == 0): ?>
      <p style="color: red;">❌ Failed to delete book.</p>
    <?php endif; ?>

    <h1>Admin Dashboard</h1>

    <a href="backend/add_book.php" class="btn">+ Add New Book</a>

    <div class="book-list">
      <?php foreach ($books as $book): ?>
        <div class="book-card">
          <img src="<?= htmlspecialchars($book['image']) ?>" alt="Cover">
          <h3><?= htmlspecialchars($book['title']) ?></h3>
          <p>Author: <?= htmlspecialchars($book['author']) ?></p>
          <p>Price: $<?= number_format($book['price'], 2) ?></p>
          
          <div class="btn-group">
            <form method="POST" action="backend/delete_book.php" onsubmit="return confirm('Are you sure you want to delete this book?');" style="display:inline;">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <button type="submit" class="btn btn-delete">Delete</button>
            </form>

            <form method="GET" action="backend/edit_book.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $book['id'] ?>">
                <button type="submit" class="btn btn-edit">Modify</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</body>
</html>
