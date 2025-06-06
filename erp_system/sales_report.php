<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    die("Access denied: Admins only.");
}
?>

<?php
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch sales records
$sales_query = "SELECT sales.id, customers.name AS customer_name, sales.total_amount, sales.sales_date 
                FROM sales 
                JOIN customers ON sales.customer_id = customers.id 
                ORDER BY sales.sales_date DESC";
$sales_result = mysqli_query($conn, $sales_query);

if (!$sales_result) {
    die("<p class='error'>ERROR: Failed to fetch sales transactions - " . mysqli_error($conn) . "</p>");
}

// Fetch products for each sale
function getSaleItems($sale_id, $conn) {
    $items_query = "SELECT products.product_name, sales_items.quantity, sales_items.price
                    FROM sales_items 
                    JOIN products ON sales_items.product_id = products.id 
                    WHERE sales_items.sale_id = '$sale_id'";
    return mysqli_query($conn, $items_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales Report</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1E1E1E;
            color: #ffffff;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #252525;
            box-shadow: 0px 0px 10px black;
            border-radius: 10px;
        }
        h1 {
            font-size: 2rem;
            color: #00ADB5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #3E3E3E;
            border-radius: 10px;
            margin-top: 20px;
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
        <h1>Sales Report</h1>

        <table>
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Sale Date</th>
                    <th>Products</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($sale = mysqli_fetch_assoc($sales_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['id']); ?></td>
                    <td><?php echo htmlspecialchars($sale['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['total_amount']); ?></td>
                    <td><?php echo htmlspecialchars($sale['sales_date']); ?></td>
                    <td>
                        <ul>
                            <?php 
                            $items_result = getSaleItems($sale['id'], $conn);
                            while ($item = mysqli_fetch_assoc($items_result)) {
                                echo "<li>{$item['product_name']} ({$item['quantity']} @ {$item['price']})</li>";
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <button onclick="window.location.href='index.php';" class="home-button">Back to Home</button>
    </div>
</body>
</html>