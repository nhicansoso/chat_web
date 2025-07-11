<?php
session_start();
header('Content-Type: application/json');
include_once 'config.php';

$response = ['success' => false];

if (isset($_SESSION['user_id'], $_POST['incoming_id'], $_POST['message'])) {
    $outgoing_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, trim($_POST['message']));

    if (!empty($message)) {
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, message, created_at)
                VALUES ('$incoming_id', '$outgoing_id', '$message', NOW())";
        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Lỗi SQL: ' . mysqli_error($conn);
        }
    } else {
        $response['error'] = 'Tin nhắn trống';
    }
} else {
    $response['error'] = 'Thiếu dữ liệu hoặc chưa đăng nhập';
}

echo json_encode($response);
