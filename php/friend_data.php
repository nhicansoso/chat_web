<?php
include_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>friendships</title>
    <link rel="stylesheet" href="./css/list_data.css">
</head>

<body>

    <?php while ($row = mysqli_fetch_assoc($query)): ?>
        <a href="users.php?user_id=<?= $row['user_id'] ?>" class="user_item">
            <div class="avatar_wrapper">
                <img src="uploads/<?= htmlspecialchars($row['avatar']) ?>" alt="Avatar">
                <i class="ri-circle-fill <?= $row['status'] === 'online' ? 'online' : 'offline' ?>"></i>
            </div>
            <div class="details">
                <span class="username"><?= htmlspecialchars($row['name']) ?></span>
                <small class="status"><?= htmlspecialchars($row['last_msg'] ?? 'Chưa có tin nhắn') ?></small>

            </div>
        </a>

    <?php endwhile; ?>

</body>

</html>