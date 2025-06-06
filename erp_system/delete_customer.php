<?php
include('db.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get customer ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p class='error'>ERROR: No customer ID provided.</p>");
}

$customer_id = $_GET['id'];

// Delete customer from database
$delete_query = "DELETE FROM customers WHERE id = '$customer_id'";
if (mysqli_query($conn, $delete_query)) {
    header("Location: customers.php"); // Redirect after successful deletion
    exit();
} else {
    die("<p class='error'>ERROR: Failed to delete customer.</p>");
}
?>