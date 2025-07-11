<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/Global.css">
    <link rel="stylesheet" href="./css/users.css">
    <link rel="stylesheet" href="./components/Detail/detail.css">
    <link rel="stylesheet" href="./components/Chat/chat.css">
    <link rel="stylesheet" href="./components/List/list.css">
    <link rel="stylesheet" href="./components/List/chatList/chatList.css">
    <link rel="stylesheet" href="./components/List/userInfo/userInfo.css">
</head>

<body>
    <div class="container">
        <?php include("components/List/List.php"); ?>
        <?php include("components/Chat/Chat.php"); ?>
        <?php include("components/Detail/Detail.php"); ?>
    </div>
</body>

</html>