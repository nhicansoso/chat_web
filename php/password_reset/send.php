<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';  // Đường dẫn ra ngoài thư mục gốc
include_once __DIR__ . '/../config.php';         // Đường dẫn tới file cấu hình


if (!isset($_POST['email']) || empty($_POST['email'])) {
    header("Location: forgot_password.php?error=empty");
    exit();
}

$email = trim($_POST['email']);

//Kiểm tra email có tồn tại trong bảng users
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

//Tạo token và thời gian hết hạn trong 1 tiếng
$token = bin2hex(random_bytes(32));
$created_at = date("Y-m-d H:i:s");
$expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

//Xoá token cũ nếu có
$stmt_delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
$stmt_delete->bind_param("s", $email);
$stmt_delete->execute();

//Lưu token mới
$stmt_insert = $conn->prepare("
    INSERT INTO password_resets (email, token, expires_at, created_at)
    VALUES (?, ?, ?, ?)
");
$stmt_insert->bind_param("ssss", $email, $token, $expires_at, $created_at);
$stmt_insert->execute();

//Tạo link reset
$reset_link = "http://localhost/chat_web/php/password_reset/reset_password.php?token=$token";

//Gửi email bằng PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'examtest1908@gmail.com';
    $mail->Password   = 'djbp ukkp vbis cqjf';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('examtest1908@gmail.com', 'Chat Realtime');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Đặt lại mật khẩu - Chat Realtime';
    $mail->Body    = "
        <h3>Xin chào,</h3>
        <p>Bạn hoặc ai đó đã yêu cầu đặt lại mật khẩu tài khoản chat của bạn.</p>
        <p>Vui lòng click vào liên kết bên dưới để thiết lập lại mật khẩu:</p>
        <p><a href='$reset_link'>$reset_link</a></p>
        <p>Liên kết có hiệu lực đến: <code>$expires_at</code></p>
        <hr>
        <p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>
    ";

    $mail->send();
    header("Location: forgot_password.php?status=sent");
} catch (Exception $e) {
    error_log("Mail Error: " . $mail->ErrorInfo);
    header("Location: forgot_password.php?error=mailfail");
}
exit();
