<?php
require_once '../includes/db.php';
session_start();

// Только админ может зайти
if (!isset($_SESSION['user_name']) || $_SESSION['user_name'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Book</title>
  <link rel="stylesheet" href="../assets/css/style.css">
<style>
  .form-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 30px;
  }
  label {
    display: block;
    margin-top: 10px;
  }
  input[type="text"],
  input[type="number"],
  input[type="file"],
  textarea,
  select {

    width: 100%;
    padding: 10px;
    margin-top: 5px;
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-family: inherit;
    font-size: 16px;
  }

  button {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
</style>
</head>
<body>
  <div class="form-container">
    <h2>Add a New Book</h2>
    <form action="save_book.php" method="POST" enctype="multipart/form-data">
      <label>Title:</label>
      <input type="text" name="title" required>

      <label>Author:</label>
      <input type="text" name="author" required>

      <label>Price (USD):</label>
      <input type="number" step="0.01" name="price" required>

      <label>Description:</label>
      <textarea name="description" rows="4" required></textarea>

      <label for="genre">Genre:</label>
      <select name="genre" id="genre" required>
        <option value="">-- Select Genre --</option>
        <option value="Programming">Programming</option>
        <option value="Self-Help">Self-Help</option>
        <option value="Fiction">Fiction</option>
        <option value="History">History</option>
        <option value="Romance">Romance</option>
        <option value="Fantasy">Fantasy</option>
      </select>

      <label>Upload Cover Image:</label>
      <input type="file" name="cover" accept="image/*" required>

      <button type="submit">Add Book</button>
    </form>
    <br>
    <a href="../admin_dashboard.php">← Back to Admin Dashboard</a>
  </div>
</body>
</html>
