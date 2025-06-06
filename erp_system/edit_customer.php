<?php
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get customer ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='error'>ERROR: No customer ID provided.</p>");
}

$customer_id = $_GET['id'];

// Fetch customer details
$customer_query = "SELECT * FROM customers WHERE id = '$customer_id'";
$customer_result = mysqli_query($conn, $customer_query);

if (!$customer_result || mysqli_num_rows($customer_result) == 0) {
    die("<p class='error'>ERROR: Customer not found.</p>");
}

$customer = mysqli_fetch_assoc($customer_result);

// Handle customer update
if (isset($_POST['update_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $update_query = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id='$customer_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: customers.php"); // Redirect to customer list after update
        exit();
    } else {
        die("<p class='error'>ERROR: Failed to update customer.</p>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Customer</title>
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
        <h1>Edit Customer</h1>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
            <button type="submit" name="update_customer">Update Customer</button>
        </form>
    </div>
</body>
</html>