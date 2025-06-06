<?php
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure database connection is valid
if (!$conn) {
    die("<p class='error'>ERROR: Database connection failed.</p>");
}

// Fetch customers
$customer_query = "SELECT * FROM customers";
$customers = mysqli_query($conn, $customer_query);

// Fetch products
$product_query = "SELECT * FROM products WHERE stock_quantity > 0";
$products = mysqli_query($conn, $product_query);

if (!$customers || !$products) {
    die("<p class='error'>ERROR: Failed to fetch customers or products!</p>");
}

// Handle sale submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['process_sale'])) {
    if (!isset($_POST['customer_id']) || !isset($_POST['total_amount']) || empty($_POST['selected_products'])) {
        die("<p class='error'>ERROR: Missing required sale details.</p>");
    }

    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];
    $selected_products = $_POST['selected_products'];

    // Insert sale into database
    $sales_query = "INSERT INTO sales (customer_id, total_amount) VALUES ('$customer_id', '$total_amount')";
    $sales_result = mysqli_query($conn, $sales_query);

    if (!$sales_result) {
        die("<p class='error'>ERROR: Failed to insert sale - " . mysqli_error($conn) . "</p>");
    }

    $sale_id = mysqli_insert_id($conn);

    foreach ($selected_products as $product_id) {
        $quantity = $_POST['quantity'][$product_id];
        $price = $_POST['price'][$product_id];

        // Validate quantity & stock
        $stock_check = mysqli_query($conn, "SELECT stock_quantity FROM products WHERE id = '$product_id'");
        $stock_data = mysqli_fetch_assoc($stock_check);

        if ($quantity <= 0 || $quantity > $stock_data['stock_quantity']) {
            die("<p class='error'>ERROR: Invalid quantity or insufficient stock for Product ID {$product_id}.</p>");
        }

        // Insert sale item
        $sales_item_query = "INSERT INTO sales_items (sale_id, product_id, quantity, price) 
                             VALUES ('$sale_id', '$product_id', '$quantity', '$price')";
        mysqli_query($conn, $sales_item_query);

        // Deduct stock
        $stock_update_query = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE id = '$product_id'";
        mysqli_query($conn, $stock_update_query);
    }

    header("Location: sales_items.php?sale_id=" . $sale_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Process Sale</title>
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
            min-height: 100vh;
        }
        .container {
            width: 60%;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #3E3E3E;
            border-radius: 10px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #555;
        }
        th {
            background-color: #00ADB5;
        }
        td {
            background-color: #252525;
        }
    </style>
    <script>
        function updateTotal() {
            let total = 0;
            const rows = document.querySelectorAll(".product-row");
            rows.forEach(row => {
                const checkbox = row.querySelector(".product-checkbox");
                if (checkbox.checked) {
                    const quantity = row.querySelector(".quantity").value;
                    const price = parseFloat(row.querySelector(".price").dataset.price);
                    if (!isNaN(quantity) && !isNaN(price)) {
                        total += quantity * price;
                    }
                }
            });
            document.getElementById("total_amount").value = total.toFixed(2);
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".product-checkbox, .quantity").forEach(input => {
                input.addEventListener("input", updateTotal);
            });
            updateTotal();
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Process a Sale</h1>

        <form method="POST">
            <label for="customer_id">Select Customer:</label>
            <select name="customer_id" required>
                <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php } ?>
            </select>

            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($products)) { ?>
                    <tr class="product-row">
                        <td><input type="checkbox" class="product-checkbox" name="selected_products[]" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td class="price" data-price="<?php echo $row['price']; ?>"><?php echo htmlspecialchars($row['price']); ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo $row['id']; ?>]" class="quantity" min="1" max="<?php echo $row['stock_quantity']; ?>" value="1">
                            <input type="hidden" name="price[<?php echo $row['id']; ?>]" value="<?php echo $row['price']; ?>">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <label for="total_amount">Total Amount:</label>
            <input type="number" id="total_amount" name="total_amount" readonly value="0.00">

            <button type="submit" name="process_sale">Submit Sale</button>
        </form>

        <button onclick="window.location.href='index.php';" class="home-button">Back to Home</button>
    </div>
</body>
</html>