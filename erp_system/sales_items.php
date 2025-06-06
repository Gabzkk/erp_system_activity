<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

// Fetch products
$product_query = "SELECT * FROM products";
$products = mysqli_query($conn, $product_query);

if (!$products) {
    die("<p class='error'>ERROR: Failed to fetch products!</p>");
}

// Handle adding sale items
if (isset($_POST['add_sale_item'])) {
    $sale_id = $_POST['sale_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Ensure values are valid
    if ($quantity <= 0 || $price <= 0) {
        die("<p class='error'>ERROR: Invalid quantity or price!</p>");
    }

    $sales_item_query = "INSERT INTO sales_items (sale_id, product_id, quantity, price) 
                         VALUES ('$sale_id', '$product_id', '$quantity', '$price')";
    $insert_result = mysqli_query($conn, $sales_item_query);

    if (!$insert_result) {
        die("<p class='error'>ERROR: Failed to add sale item!</p>");
    }

    // Deduct stock
    $stock_update_query = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE id = '$product_id'";
    $stock_result = mysqli_query($conn, $stock_update_query);

    if (!$stock_result) {
        die("<p class='error'>ERROR: Failed to update stock!</p>");
    }

    echo "<p class='success'>Sale item added successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Sale Items</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1E1E1E;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            width: 50%;
            background: #252525;
            padding: 30px;
            box-shadow: 0px 0px 10px black;
            border-radius: 10px;
            text-align: center;
        }
        h1 {
            font-size: 2rem;
            color: #00ADB5;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        select, input, button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        select, input {
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
        .error {
            color: red;
            font-size: 1.2rem;
            margin-top: 15px;
        }
        .success {
            color: #00ADB5;
            font-size: 1.2rem;
            margin-top: 15px;
        }
        .home-button {
            margin-top: 15px;
            background-color: #FF3E3E;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            color: white;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }
        .home-button:hover {
            background-color: #C20000;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Products to Sale</h1>

        <form method="POST">
            <input type="hidden" name="sale_id" value="<?php echo isset($_GET['sale_id']) ? htmlspecialchars($_GET['sale_id']) : ''; ?>">
            <label for="product_id">Select Product:</label>
            <select name="product_id" required>
                <?php while ($row = mysqli_fetch_assoc($products)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['product_name']); ?></option>
                <?php } ?>
            </select>
            <input type="number" name="quantity" placeholder="Quantity" required>
            <input type="number" name="price" placeholder="Price Per Unit" step="0.01" required>
            <button type="submit" name="add_sale_item">Add to Sale</button>
        </form>

        <button onclick="window.location.href='index.php';" class="home-button">Back to Home</button>
    </div>
</body>
</html>