<?php
session_start();
include_once 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "UPDATE users SET last_seen = NOW(), status = 'offline' WHERE user_id = '$user_id'");
    session_destroy();
}

header("Location: ../index.php");
exit();
