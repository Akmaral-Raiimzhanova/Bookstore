<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Online Bookstore</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php if (isset($_SESSION["user_name"])): ?>
    <div class="auth-header">
        <div class="left-part">
            <strong>Hello <?= htmlspecialchars($_SESSION["user_name"]); ?>!</strong>
        </div>
        <div class="right-part">
            <div>
                <a href="logout.php">Log out</a>
                <a> | </a>
                <a href="cart.php" class="cart-link">🛒</a>
            </div>
            <?php if ($_SESSION["role"] === 'admin'): ?>
                <div class="admin-panel-link">
                    <a href="admin_dashboard.php" class="admin-button">Admin Panel</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="auth-links">
       <a href="login.php">Log in</a> | <a href="register.php">Register</a>
       <a> | </a> <a href="cart.php" class="cart-link">🛒 </a>
    </div>
<?php endif; ?>

<h1 class="main-title">Welcome to BookNest!</h1>

<!-- Поисковая форма -->
<form method="get" action="index.php" class="search-form">
    <input type="text" name="q" placeholder="Search by title or author..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
    <button type="submit">🔍</button>
</form>

<section class="book-stand">
  <div class="book-stand-text">
    <span class="bestseller-label">Today's Bestseller</span>
    <h2>NEW FROM<br><strong>SARAH J. MAAS</strong></h2>
    <p>Limited editions that capture the magic<br>and beauty of the Night Court.</p>
  </div>

  <div class="book-single">
    <img src="assets/img/animated/book1.png" alt="Book">
  </div>
</section>

<?php
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY genre, title";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM books ORDER BY genre, title";
    $result = $conn->query($sql);
}

$booksByGenre = [];

while ($row = $result->fetch_assoc()) {
    $genre = $row['genre'];
    if (!isset($booksByGenre[$genre])) {
        $booksByGenre[$genre] = [];
    }
    $booksByGenre[$genre][] = $row;
}
?>

<?php foreach ($booksByGenre as $genre => $books): ?>
  <?php if (trim($genre) === '') continue; ?>

  <section class="genre-section">
    <h2 class="genre-heading"><?= strtoupper(htmlspecialchars($genre)) ?></h2>

    <div class="book-row">
      <?php foreach ($books as $book): ?>
        <div class="book-card">
          <img src="<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
          <h3><?= htmlspecialchars($book['title']) ?></h3>
          <p class="author"><?= htmlspecialchars($book['author']) ?></p>
          <p class="price"><?= number_format($book['price'], 2) ?> USD</p>
          <button onclick="addToCart(<?= $book['id'] ?>)">Add to Cart</button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endforeach; ?>

<!-- Уведомление о добавлении в корзину -->
<div id="cart-notify" class="notify"></div>

<script src="assets/js/script.js"></script>
</body>
</html>
