// Показывает всплывающее сообщение
function showNotification(message) {
    const notify = document.getElementById("cart-notify");
    notify.textContent = message;
    notify.style.display = "block";

    setTimeout(() => {
        notify.style.display = "none";
    }, 3000);
}

// Добавляет книгу в корзину
function addToCart(bookId) {
    fetch("add_to_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "book_id=" + bookId
    })
    .then(response => response.text())
    .then(data => {
        if (data === "not_logged_in") {
            alert("Please log in to add books to your cart.");
            window.location.href = "login.php";
        } else if (data === "added") {
            showNotification("Book added to your cart!");
        } else if (data === "updated") {
            showNotification("Book quantity updated in your cart.");
        } else {
            alert("Something went wrong.");
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while adding the book.");
    });
}
