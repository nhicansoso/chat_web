<?php
session_start();
include_once 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE users SET last_seen = NOW(), status = 'online' WHERE user_id = '$user_id'");
}

$sql = "UPDATE users SET status = 'offline' 
        WHERE TIMESTAMPDIFF(SECOND, last_seen, NOW()) > 10";
mysqli_query($conn, $sql);
