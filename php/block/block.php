<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['user_id'], $_POST['receiver_id'], $_POST['action'])) {
    http_response_code(400);
    exit("Invalid request");
}


$user_id = $_SESSION['user_id'];
$receiver_id = intval($_POST['receiver_id']);
$action = $_POST['action'];

if ($action === 'block') {
    // Kiểm tra đã block chưa
    $stmt = $conn->prepare("SELECT * FROM friendships 
        WHERE requester_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $user_id, $receiver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE friendships SET status = 'block' 
            WHERE requester_id = ? AND receiver_id = ?");
        $stmt->bind_param("ii", $user_id, $receiver_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO friendships 
            (requester_id, receiver_id, status, created_at)
            VALUES (?, ?, 'block', NOW())");
        $stmt->bind_param("ii", $user_id, $receiver_id);
        $stmt->execute();
    }
} elseif ($action === 'unblock') {
    // Chỉ xóa nếu chính mình là người block
    $stmt = $conn->prepare("DELETE FROM friendships 
        WHERE status = 'block' AND requester_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $user_id, $receiver_id);
    $stmt->execute();
}

header("Location: ../../users.php?user_id=$receiver_id");
exit();
