<?php
session_start();
header('Content-Type: application/json');
include_once '../config.php';

$response = ['success' => false];

if (!isset($_SESSION['user_id'], $_POST['incoming_id'])) {
    $response['error'] = 'Thiếu dữ liệu hoặc chưa đăng nhập';
    echo json_encode($response);
    exit;
}

$outgoing_id = intval($_SESSION['user_id']);
$incoming_id = intval($_POST['incoming_id']);
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$imagePath = null;

// Xử lý ảnh nếu có
if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === 0 &&
    is_uploaded_file($_FILES['image']['tmp_name'])
) {
    $img_name = $_FILES['image']['name'];
    $img_tmp = $_FILES['image']['tmp_name'];
    $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($img_ext, $allowed_ext)) {
        $new_name = uniqid('img_', true) . '.' . $img_ext;
        $upload_dir = '../../uploads/messages/';
        $upload_path = $upload_dir . $new_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($img_tmp, $upload_path)) {
            $imagePath = 'uploads/messages/' . $new_name;
        } else {
            $response['error'] = 'Không thể tải ảnh lên máy chủ';
            echo json_encode($response);
            exit;
        }
    } else {
        $response['error'] = 'Chỉ cho phép ảnh JPG, PNG, GIF, WEBP';
        echo json_encode($response);
        exit;
    }
}

// Lưu tin nhắn nếu có văn bản hoặc ảnh
if ($message !== '' || $imagePath !== null) {
    $stmt = $conn->prepare("
        INSERT INTO messages (incoming_msg_id, outgoing_msg_id, message, image, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iiss", $incoming_id, $outgoing_id, $message, $imagePath);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Lỗi khi lưu tin nhắn: ' . $stmt->error;
    }

    $stmt->close();
} else {
    $response['error'] = 'Bạn chưa nhập tin nhắn hoặc chọn ảnh.';
}

echo json_encode($response);
