<?php
include_once 'config.php';
session_start();

$outgoing_id = $_SESSION['user_id'] ?? null;
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm'] ?? '');

if (!$outgoing_id) {
  die("Không có phiên người dùng");
}

$sql = "
    SELECT u.*, 
        (
            SELECT message
            FROM messages 
            WHERE 
                (incoming_msg_id = u.user_id AND outgoing_msg_id = {$outgoing_id}) OR
                (incoming_msg_id = {$outgoing_id} AND outgoing_msg_id = u.user_id)
            ORDER BY message_id DESC 
            LIMIT 1
        ) AS last_msg
    FROM users u
    JOIN friendships f 
      ON (
        (f.requester_id = {$outgoing_id} AND f.receiver_id = u.user_id)
        OR
        (f.receiver_id = {$outgoing_id} AND f.requester_id = u.user_id)
      )
    WHERE f.status = 'accepted'
    AND (u.name LIKE '%{$searchTerm}%' OR u.email LIKE '%{$searchTerm}%')
    ORDER BY u.name ASC
";

$query = mysqli_query($conn, $sql);

if (!$query) {
  die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) > 0) {
  include_once "friend_data.php";
} else {
}
