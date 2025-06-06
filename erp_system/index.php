<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>ERP System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        nav {
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: #1E1E1E;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
        }
        nav a, .logout-button {
            text-decoration: none;
            color: #00ADB5;
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #222;
            text-align: center;
            transition: all 0.3s ease;
            display: block;
        }
        nav a:hover, .logout-button:hover {
            background-color: #00ADB5;
            color: #121212;
        }
    </style>
</head>
<body>
    <h1>Welcome to ERP System</h1>
    <nav>
        <a href="products.php">Product Management</a>
        <a href="customers.php">Customer Management</a>
        <a href="sales.php">Sales Processing</a>
        <a href="sales_items.php">Check Items</a>
        <a href="sales_report.php">View Sales Report</a>
        <a href="logout.php" class="logout-button">Logout</a>
    </nav>
</body>
</html>