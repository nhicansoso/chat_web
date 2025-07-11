<?php
include_once 'php/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$row = null;
if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id = {$user_id}");

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }
}
$display = $row ? 'flex' : 'none';
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
    <div class="chat" style="display: <?php echo $display; ?>">
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
        <div class="center" id="messageContainer"></div>


        <!-- Bottom input/chat actions -->
        <form action="#" class="bottom" id="chatForm">
            <input type="hidden" id="incoming_id" value="<?= $row['user_id'] ?>">
            <div class="icon">
                <i class="ri-image-line"></i>
                <i class="ri-camera-2-line"></i>
                <i class="ri-mic-line"></i>
            </div>
            <input type="text" id="messageInput" placeholder="Type a message..." autocomplete="off" />
            <button class="sendButton" type="submit">Send</button>
        </form>

    </div>
</body>

</html>
<script src="js/chat.js"></script>

<script>
    const center = document.querySelector('#messageContainer');
    center.scrollTop = center.scrollHeight;
</script>