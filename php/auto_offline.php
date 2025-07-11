<?php
include_once 'config.php';

$sql = "UPDATE users SET status = 'offline' 
        WHERE TIMESTAMPDIFF(SECOND, last_seen, NOW()) > 10";
mysqli_query($conn, $sql);
