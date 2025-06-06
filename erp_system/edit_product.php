<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');

// Get product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='error'>ERROR: No product ID provided.</p>");
}

$product_id = $_GET['id'];

// Fetch product details
$product_query = "SELECT * FROM products WHERE id = '$product_id'";
$product_result = mysqli_query($conn, $product_query);

if (!$product_result || mysqli_num_rows($product_result) == 0) {
    die("<p class='error'>ERROR: Product not found.</p>");
}

$product = mysqli_fetch_assoc($product_result);

// Handle product update
if (isset($_POST['update_product'])) {
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    $update_query = "UPDATE products SET product_name='$name', category='$category', price='$price', stock_quantity='$stock' WHERE id='$product_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: products.php"); // Redirect to product list after update
        exit();
    } else {
        die("<p class='error'>ERROR: Failed to update product.</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1E1E1E;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            width: 40%;
            background: #252525;
            padding: 20px;
            box-shadow: 0px 0px 10px black;
            border-radius: 10px;
            text-align: center;
        }
        input, button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }
        input {
            background-color: #3E3E3E;
            color: #ffffff;
        }
        button {
            background-color: #00ADB5;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background-color: #007F88;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST">
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
            <input type="number" name="stock_quantity" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required>
            <button type="submit" name="update_product">Update Product</button>
        </form>
    </div>
</body>
</html>