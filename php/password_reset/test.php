<?php
include_once '../config.php';

if (!isset($_POST['email']) || empty($_POST['email'])) {
    header("Location: forgot_password.php?error=empty");
    exit();
}

$email = trim($_POST['email']);

// 1. Kiểm tra email có tồn tại trong bảng users
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
if (!$stmt) {
    die("SQL Error (SELECT): " . $conn->error);
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: forgot_password.php?error=notfound");
    exit();
}

// 2. Tạo token và thời gian hết hạn 
$token = bin2hex(random_bytes(32));
$created_at = date("Y-m-d H:i:s");                          // Thời điểm hiện tại
$expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));    // Hết hạn sau 1 giờ

// 3. Xoá token cũ (nếu có)
$stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
if (!$stmt_delete) {
    die("SQL Error (DELETE): " . $conn->error);
}
$stmt_delete->bind_param("s", $email);
$stmt_delete->execute();

// 4. Lưu token mới
$stmt_insert = $conn->prepare("
    INSERT INTO password_resets (email, token, expires_at, created_at)
    VALUES (?, ?, ?, ?)
");
if (!$stmt_insert) {
    die("SQL Error (INSERT): " . $conn->error);
}
$stmt_insert->bind_param("ssss", $email, $token, $expires_at, $created_at);
$stmt_insert->execute();

// 5. Tạo link reset
$reset_link = "http://localhost/chat_web/php/password_reset/reset_password.php?token=$token";

echo "<h2>Reset link đã tạo thành công</h2>";
echo "<p><strong>Link đặt lại mật khẩu:</strong></p>";
echo "<a href='$reset_link'>$reset_link</a>";
echo "<p><i>Hiệu lực từ:</i> <code>$created_at</code></p>";
echo "<p><i>Đến hết:</i> <code>$expires_at</code></p>";
exit();