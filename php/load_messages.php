<?php
session_start();
include_once 'config.php';


$outgoing_id = $_SESSION['user_id'] ?? '';
$incoming_id = $_POST['incoming_id'] ?? '';

$output = "";

if (!$outgoing_id || !$incoming_id) {
    echo "<div class='message'><p>Lỗi: Thiếu ID người dùng.</p></div>";
    exit();
}

$sql = "
    SELECT * FROM messages 
    WHERE 
        (incoming_msg_id = '$incoming_id' AND outgoing_msg_id = '$outgoing_id')
        OR 
        (incoming_msg_id = '$outgoing_id' AND outgoing_msg_id = '$incoming_id')
    ORDER BY created_at ASC
";

$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $isOwn = $row['outgoing_msg_id'] === $outgoing_id;
        $msg = htmlspecialchars($row['message']);
        $time = date('H:i', strtotime($row['created_at']));

        $output .= '<div class="message ' . ($isOwn ? 'own' : '') . '">
            <div class="text">
                <p>' . $msg . '</p>
                <span>' . $time . '</span>
            </div>
        </div>';
    }
} else {
    $output .= "<div class='message'><p>Chưa có tin nhắn nào.</p></div>";
}

echo $output;
