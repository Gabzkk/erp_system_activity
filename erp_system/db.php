<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'erp_system';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Database connection error: ' . mysqli_connect_error());
}
?>