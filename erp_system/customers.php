<?php
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle customer addition
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
    mysqli_query($conn, $query);
}

// Fetch customers
$query = "SELECT * FROM customers";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Management</title>
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
        <h1>Customer Management</h1>

        <form method="POST">
            <input type="text" name="name" placeholder="Customer Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit" name="add_customer">Add Customer</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <a href="edit_customer.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_customer.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <button onclick="window.location.href='index.php';" class="home-button">Back to Home</button>
    </div>
</body>
</html>