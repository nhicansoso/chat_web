<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once 'config_secret.php';

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Kết nối database thất bại: " . mysqli_connect_error());
}
$conn->query("SET time_zone = '+07:00'");
