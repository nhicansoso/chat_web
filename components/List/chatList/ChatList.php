<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat List</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="chatlist">
        <div class="search">
            <div class="searchBar">
                <button class="closeBtn"><i class="ri-close-line"></i></button>
                <button class="searchBtn"><i class="ri-search-2-line"></i></button>
                <input type="text" placeholder="Search">
            </div>
        </div>

        <div class="users_list"></div>
        <div class="users_chat"></div>
    </div>
</body>

<script src="js/list.js"></script>

</html>