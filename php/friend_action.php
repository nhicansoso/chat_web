<?php
session_start();
include_once 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
$other_id = $_POST['receiver_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$user_id || !$other_id || !$action) {
    http_response_code(400);
    exit("Invalid request");
}

if ($user_id == $other_id) exit("Cannot friend yourself");

// Không cho gửi nếu đang bị block
$blockCheck = $conn->prepare("SELECT * FROM friendships WHERE 
    ((requester_id = ? AND receiver_id = ?) OR (requester_id = ? AND receiver_id = ?)) 
    AND status = 'block'");
$blockCheck->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
$blockCheck->execute();
if ($blockCheck->get_result()->num_rows > 0) exit("Blocked");

if ($action === 'send') {
    $stmt = $conn->prepare("SELECT * FROM friendships WHERE 
        (requester_id = ? AND receiver_id = ?) OR (requester_id = ? AND receiver_id = ?)");
    $stmt->bind_param("iiii", $user_id, $other_id, $other_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['status'] === 'rejected' || $row['status'] === 'none') {
            $stmt = $conn->prepare("UPDATE friendships SET status = 'pending', requester_id = ?, receiver_id = ?, created_at = NOW()
                                    WHERE friend_id = ?");
            $stmt->bind_param("iii", $user_id, $other_id, $row['friend_id']);
            $stmt->execute();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO friendships (requester_id, receiver_id, status, created_at)
                                VALUES (?, ?, 'pending', NOW())");
        $stmt->bind_param("ii", $user_id, $other_id);
        $stmt->execute();
    }
} elseif ($action === 'cancel') {
    $stmt = $conn->prepare("DELETE FROM friendships 
                            WHERE requester_id = ? AND receiver_id = ? AND status = 'pending'");
    $stmt->bind_param("ii", $user_id, $other_id);
    $stmt->execute();
} elseif ($action === 'accept') {
    $stmt = $conn->prepare("UPDATE friendships SET status = 'accepted' 
                            WHERE requester_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $other_id, $user_id);
    $stmt->execute();
} elseif ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE friendships SET status = 'rejected' 
                            WHERE requester_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $other_id, $user_id);
    $stmt->execute();
}

exit("OK");
