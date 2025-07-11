<?php
session_start();
include_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    die("You are not logged in.");
}

$outgoing_id = $_SESSION['user_id'];
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$sql = "SELECT * FROM users WHERE user_id != {$outgoing_id} AND name LIKE '%{$searchTerm}%'";
$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Error SQL: " . mysqli_error($conn));
}

$output = "";
if (mysqli_num_rows($query) > 0) {
    include_once "search_data.php";
} else {
    $output = "<p style='padding: 10px 20px; color: #ccc;'>No users found.</p>";
}
echo $output;
