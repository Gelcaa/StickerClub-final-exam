<?php
session_start(); // Start the session
if (isset($_POST["row_index"])) {
    $rowIndex = $_POST["row_index"];

    if (array_key_exists($rowIndex, $_SESSION["cart_items"])) {
        unset($_SESSION["cart_items"][$rowIndex]);
    }
}

// Redirect back to the shopping cart page or any other appropriate page


// Check if the cart items exist in the session
if (isset($_SESSION["cart_items"])) {
    // Display cart items
    echo "<div><h1>Cart Items</h1></div>";
    if (!empty($_SESSION["cart_items"])) {
    foreach ($_SESSION["cart_items"] as $key => $cartItem) {
        echo "<tr>";
        echo "<td>" . $cartItem["product"] . "</td>";
        echo "<td>";
        echo "<div class='quantity-input'>";
        echo "<input type='number' id='quantity' name='quantity' min='1' max='99' value='" . $cartItem["quantity"] . "'>";
        echo "</div>";
        echo "</td>";
        echo "<td>";
        echo "<form action='checkout.php' method='post'>";
        echo "<input type='hidden' name='row_index' value='" . $key . "'>";
        echo "<button type='submit' name='delete_row'>Delete</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No items in the cart.</p>";
}


    if (isset($_POST["checkout"])) {
        // Establish database connection
        $conn = mysqli_connect("localhost", "root", "", "stickerclubdb");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        foreach ($_SESSION["cart_items"] as $cartItem) {
            $username = $cartItem["username"];
            $product = $cartItem["product"];
            $quantity = $cartItem["quantity"];

            // Check if the customer exists
            $sql = "SELECT username FROM customertbl WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                // Fetch the customer's username
                $row = mysqli_fetch_assoc($result);
                $customer_username = $row["username"];

                // Check if the product already exists in the order_items table
                $sql = "SELECT quantity FROM order_items WHERE customer_username = ? AND product_name = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $customer_username, $product);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    // Product already exists, update the quantity
                    $row = mysqli_fetch_assoc($result);
                    $existingQuantity = $row["quantity"];

                    $newQuantity = $existingQuantity + $quantity;

                    $updateSql = "UPDATE order_items SET quantity = ? WHERE customer_username = ? AND product_name = ?";
                    $updateStmt = mysqli_prepare($conn, $updateSql);
                    mysqli_stmt_bind_param($updateStmt, "iss", $newQuantity, $customer_username, $product);
                    mysqli_stmt_execute($updateStmt);
                    mysqli_stmt_close($updateStmt);
                } else {
                    // Product does not exist, insert a new row
                    $insertSql = "INSERT INTO order_items (customer_username, product_name, quantity) VALUES (?, ?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertSql);
                    mysqli_stmt_bind_param($insertStmt, "ssi", $customer_username, $product, $quantity);
                    mysqli_stmt_execute($insertStmt);
                    mysqli_stmt_close($insertStmt);
                }
            } else {
                echo "Invalid customer.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($conn);

        // Clear the cart items from the session
        $_SESSION["cart_items"] = [];

        // Display order complete popup
        echo '<script>alert("Order Complete");</script>';

        // Redirect back to home_products.php
        echo '<script>window.location.href = "home_products.php";</script>';
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
        <link href="checkout_style.css" rel="stylesheet">

    <title>Checkout</title>

</head>
<body>
    <?php if (!empty($_SESSION["cart_items"])): ?>
     <form action="checkout.php" method="post">
            <input type="hidden" name="username" value="<?php echo urlencode($username); ?>">
            <button type="submit" name="checkout">Checkout</button>
        </form>
          
    <?php endif; ?>
   <button class="back_button" name="back" onclick="window.location.href = 'home_products.php';">Back to Products</button>

</body>
</html>
