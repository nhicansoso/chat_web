<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config.php';
date_default_timezone_set('Asia/Ho_Chi_Minh'); //set thời gian

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || empty($password)) {
    die("Invalid request. Missing token or password.");
}

//Truy vấn token và thời hạn
$stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
if (!$stmt) {
    die("SQL Error (SELECT): " . $conn->error);
}
$stmt->bind_param("s", $token);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $email = $row['email'];
    $expires_at = $row['expires_at'];

    //So sánh bằng PHP thay vì NOW()
    if (strtotime($expires_at) > time()) {
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        //Cập nhật mật khẩu
        $stmt2 = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        if (!$stmt2) {
            die("SQL Error (UPDATE): " . $conn->error);
        }
        $stmt2->bind_param("ss", $hashed_pass, $email);
        $stmt2->execute();

        //Xoá token sau khi dùng
        $stmt3 = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt3->bind_param("s", $email);
        $stmt3->execute();

        echo "<h2>Password has been changed successfully.</h2>";
        echo "<p><a href='../../index.php'>Login now</a></p>";
    } else {
        echo "<h3 style='color: red;'>Token đã hết hạn.</h3>";
        echo "<p>Server PHP (NOW): <code>" . date("Y-m-d H:i:s") . "</code></p>";
        echo "<p>expires_at trong DB: <code>" . $expires_at . "</code></p>";
    }
} else {
    echo "<h3 style='color: red;'>Token không tồn tại.</h3>";
}
