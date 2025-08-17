<?php
session_start();
include_once '../config.php';

$outgoing_id = $_SESSION['user_id'] ?? '';
$incoming_id = $_POST['incoming_id'] ?? '';

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
$output = "";

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $isOwn = $row['outgoing_msg_id'] == $outgoing_id;
        $msg = htmlspecialchars($row['message']);
        $img = htmlspecialchars($row['image']);
        $time = date('H:i', strtotime($row['created_at']));

        $output .= '<div class="message ' . ($isOwn ? 'own' : '') . '">';
        $output .= '<div class="text">';

        // Nếu có ảnh thì hiện ảnh
        if (!empty($img)) {
            $output .= "<img src='/chat_web/$img' alt='Hình ảnh' class='chat-image'><br>";
        }

        // Nếu có văn bản thì hiện văn bản
        if (!empty($msg)) {
            $output .= '<p class="message-text">' . $msg . '</p>';
        }

        $output .= "<span>$time</span>";
        $output .= '</div></div>';
    }
} else {
    $output .= "<div class='message'><p>Chưa có tin nhắn nào.</p></div>";
}

echo $output;
