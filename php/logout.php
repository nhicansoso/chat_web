<?php
session_start();
if (isset($_SESSION['user_id'])) {
    include_once 'config.php';

    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET status = 'offline' WHERE user_id = '$user_id'";
    mysqli_query($conn, $sql);

    session_unset();
    session_destroy();

    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
