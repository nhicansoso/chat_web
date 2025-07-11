<?php
include_once 'php/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$user_id}");
if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
} else {
    header("Location: users.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="chat">
        <!-- Top user info -->
        <div class="top">
            <div class="user">
                <img class="userimg" src="uploads/<?php echo htmlspecialchars($row['avatar']); ?>" alt="img_avatar">
                <div class="text">
                    <span class="username"><?php echo htmlspecialchars($row['name']); ?></span>
                    <div class="status"><?php echo htmlspecialchars($row['status'] ?? 'Offline'); ?></div>
                </div>
            </div>
        </div>

        <!-- Center chat messages -->
        <div class="center">
            <div class="message">
                <img src="./avatar.png" alt="avatar" />
                <div class="text">
                    <p>Hello, this is a test message.</p>
                    <span>1 min ago</span>
                </div>
            </div>

            <div class="message own">
                <img src="./background.jpg" alt="avatar" />
                <div class="text">
                    <img src="./background.jpg" alt="img" />
                    <p>This is my reply.</p>
                    <span>Just now</span>
                </div>
            </div>

            <div class="message">
                <img src="./avatar.png" alt="avatar" />
                <div class="text">
                    <p>Hello, this is a test message.</p>
                    <span>1 min ago</span>
                </div>
            </div>

            <div class="message own">
                <img src="./background.jpg" alt="avatar" />
                <div class="text">
                    <p>This is my reply.</p>
                    <span>Just now</span>
                </div>
            </div>
        </div>

        <!-- Bottom input/chat actions -->
        <div class="bottom">
            <div class="icon">
                <i class="ri-image-line"></i>
                <i class="ri-camera-2-line"></i>
                <i class="ri-mic-line"></i>
            </div>

            <input type="text" placeholder="Type a message..." />

            <div class="emoji">
                <i class="ri-emotion-line"></i>
                <!-- Để dùng emoji picker, hãy bổ sung JS tại đây -->
            </div>

            <button class="sendButton">Send</button>
        </div>
    </div>
</body>

</html>

<script>
    const center = document.querySelector('.chat .center');
    center.scrollTop = center.scrollHeight;
</script>