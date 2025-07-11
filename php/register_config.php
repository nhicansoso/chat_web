<?php
include_once 'config.php';

$name = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($name) && !empty($email) && !empty($password)) {
    $sql = "SELECT email FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Email đã tồn tại!";
    } else {
        $avatar_name = 'default.jpg';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $img_name = $_FILES['avatar']['name'];
            $img_tmp = $_FILES['avatar']['tmp_name'];
            $img_size = $_FILES['avatar']['size'];
            $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($img_ext, $allowed_ext)) {
                if ($img_size <= 2 * 1024 * 1024) {
                    $avatar_name = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $img_name);
                    $upload_path = '../uploads/' . $avatar_name;
                    if (!move_uploaded_file($img_tmp, $upload_path)) {
                        echo "Lỗi khi upload ảnh!";
                        exit;
                    }
                } else {
                    echo "Ảnh quá lớn. Vui lòng chọn ảnh dưới 2MB!";
                    exit;
                }
            } else {
                echo "Chỉ chấp nhận file ảnh jpg, jpeg, png, gif!";
                exit;
            }
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO users (name, email, password, avatar) VALUES ('$name', '$email', '$hashed_password', '$avatar_name')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "Đăng ký thành công!";
            exit;
        } else {
            echo "Đăng ký thất bại: " . mysqli_error($conn);
        }
    }
} else {
    echo "Vui lòng điền đầy đủ thông tin!";
}
