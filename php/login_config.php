<?php
session_start();
include_once 'config.php';
// Nếu đã đăng nhập, cập nhật trạng thái người dùng cũ thành "offline"
// if (isset($_SESSION['user_id'])) {
//     $old_user_id = $_SESSION['user_id'];
//     $sql = "UPDATE users SET status = 'offline' WHERE user_id = '$old_user_id'";
//     mysqli_query($conn, $sql);

//     session_unset();
//     session_destroy();
//     session_start();
// }
// Xử lý đăng nhập
$emailname = mysqli_real_escape_string($conn, $_POST['emailname'] ?? '');
$password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
// Kiểm tra xem (email hoặc name) và mật khẩu
if (!empty($emailname) && !empty($password)) {
    $sql = "SELECT * FROM users WHERE email = '$emailname' or name = '$emailname'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $user_id = $row['user_id'];
            $update_sql = "UPDATE users SET status = 'online', last_seen = NOW() WHERE user_id = '{$row['user_id']}'";
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
