<?php
$isBlocker = false;
$isBlockedByOther = false;

$me = $_SESSION['user_id'] ?? null;
$other = $row['user_id'] ?? null;

if ($conn && $me && $other && $friend_status === 'block') {
    $stmt = $conn->prepare("
        SELECT requester_id FROM friendships 
        WHERE ((requester_id = ? AND receiver_id = ?) OR (requester_id = ? AND receiver_id = ?))
        AND status = 'block'
        LIMIT 1
    ");
    $stmt->bind_param("iiii", $me, $other, $other, $me);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($info = $res->fetch_assoc()) {
        $isBlocker = ($info['requester_id'] == $me);
        $isBlockedByOther = !$isBlocker;
    }
}
