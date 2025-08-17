<?php
include_once 'config.php';

$name = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (!empty($name) && !empty($email) && !empty($password)) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email!";
        exit;
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Email already taken!";
        exit;
    }

    $stmt = $conn->prepare("SELECT name FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username already taken!";
        exit;
    }

    $avatar_name = 'default.jpg';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES['avatar']['name'];
        $img_temp = $_FILES['avatar']['tmp_name'];
        $img_size = $_FILES['avatar']['size'];
        $img_type = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_type = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_type, $allowed_type)) {
            if ($img_size <= 2 * 1024 * 1024) {
                $avatar_name = uniqid('', true) . '.' . $img_type;
                $upload_path = '../uploads/' . $avatar_name;
                if (!move_uploaded_file($img_temp, $upload_path)) {
                    echo "Upload failed!";
                    exit;
                }
            } else {
                echo "Image too large. Max 2MB!";
                exit;
            }
        } else {
            echo "Only jpg, jpeg, png, gif allowed!";
            exit;
        }
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, avatar) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("ssss", $name, $email, $hashed_password, $avatar_name);

    if ($insert_stmt->execute()) {
        echo "Registered successfully!";
        exit;
    } else {
        echo "Registration failed: " . $conn->error;
    }
} else {
    echo "Please fill in all required fields!";
}
