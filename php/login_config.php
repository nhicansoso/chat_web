<?php
session_start();
include_once 'config.php';
// Nếu đã đăng nhập, cập nhật trạng thái người dùng cũ thành "offline"
if (isset($_SESSION['user_id'])) {
    $old_user_id = $_SESSION['user_id'];
    $sql = "UPDATE users SET status = 'offline' WHERE user_id = '$old_user_id'";
    mysqli_query($conn, $sql);

    session_unset();
    session_destroy();
    session_start();
}
// Xử lý đăng nhập
$nameOrEmail = mysqli_real_escape_string($conn, $_POST['nameOrEmail'] ?? '');
$password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
// Kiểm tra xem email và mật khẩu
if (!empty($nameOrEmail) && !empty($password)) {
    $sql = "SELECT * FROM users WHERE email = '$nameOrEmail' OR name = '$nameOrEmail' ";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $status = "online";
            $user_id = $row['user_id'];
            $update_sql = "UPDATE users SET status = '$status' WHERE user_id = '$user_id'";
            mysqli_query($conn, $update_sql);
            echo "success";
            exit();
        } else {
            echo "Mật khẩu không đúng!";
        }
    } else {
        echo "Tài khoản không tồn tại!";
    }
} else {
    echo "Vui lòng nhập đầy đủ thông tin!";
}
