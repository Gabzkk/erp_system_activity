<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

// Fetch products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("ERROR: QUERY FAILED! " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1E1E1E;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #252525;
            padding: 20px;
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
            margin-bottom: 20px;
        }
        input, button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
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
        a {
            text-decoration: none;
            color: #FF3E3E;
            font-weight: bold;
            transition: 0.3s;
        }
        a:hover {
            color: #C20000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Management</h1>

        <form action="product_controller.php" method="POST">
            <input type="text" name="product_name" placeholder="Product Name" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock_quantity" placeholder="Stock Quantity" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['stock_quantity']); ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <button onclick="window.location.href='index.php';" class="home-button">Back to Home</button>
    </div>
</body>
</html>