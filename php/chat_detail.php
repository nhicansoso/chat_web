<?php
include_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$row = null;
if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$user_id}");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
}

$display = $row ? 'flex ' : 'none';
$requester_id = $_SESSION['user_id'];

$friend_status = 'none';
if ($row) {
    $friend_data = getFriendStatus($conn, $_SESSION['user_id'], $row['user_id']);
    $friend_status = $friend_data['status'];
    $requester_id = $friend_data['requester_id'];
}

$isBlocker = false;
$isBlockedByOther = false;

if ($friend_status === 'block') {
    $userId = $_SESSION['user_id'];
    $otherUserId = $row['user_id'];

    $sql = "SELECT requester_id FROM friendships 
            WHERE ((requester_id = ? AND receiver_id = ?) 
                OR (requester_id = ? AND receiver_id = ?)) 
              AND status = 'block'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $userId, $otherUserId, $otherUserId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($info = $result->fetch_assoc()) {
        if ($info['requester_id'] == $userId) {
            $isBlocker = true;
        } else {
            $isBlockedByOther = true;
        }
    }
}

function getFriendStatus($conn, $userId, $otherUserId)
{
    $sql = "SELECT status, requester_id FROM friendships 
            WHERE (requester_id = ? AND receiver_id = ?) 
               OR (requester_id = ? AND receiver_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $userId, $otherUserId, $otherUserId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return ['status' => $row['status'], 'requester_id' => $row['requester_id']];
    }
    return ['status' => 'none', 'requester_id' => null];
}
