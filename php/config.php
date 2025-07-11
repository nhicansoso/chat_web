<?php
require_once 'config_secret.php';

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
