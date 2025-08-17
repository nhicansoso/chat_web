<?php
include_once 'php/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include_once 'php/chat_detail.php';
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
            <?php if ($friend_status === 'none' || $friend_status === 'rejected'): ?>
                <button class="friend_btn" data-id="<?= $row['user_id'] ?>" data-action="send">Add Friend</button>

            <?php elseif ($friend_status === 'pending' && $requester_id == $_SESSION['user_id']): ?>
                <button class="friend_btn" data-id="<?= $row['user_id'] ?>" data-action="cancel">Cancel Request</button>

            <?php elseif ($friend_status === 'pending' && $requester_id != $_SESSION['user_id']): ?>
                <div class="friend_actions">
                    <button class="friend_btn" data-id="<?= $row['user_id'] ?>" data-action="accept">Accept</button>
                    <button class="friend_btn reject" data-id="<?= $row['user_id'] ?>" data-action="reject">Reject</button>
                </div>

            <?php elseif ($friend_status === 'accepted'): ?>
                <span class="friend_tag"><i class="ri-user-line"></i></span>
            <?php endif; ?>




        </div>

        <!-- Center chat messages -->
        <div class="center" id="messageContainer"></div>


        <!-- Bottom input/chat actions -->
        <?php if (!$isBlockedByOther): ?>
            <form action="#" class="bottom" id="chatForm" enctype="multipart/form-data">
                <input type="hidden" id="incoming_id" name="incoming_id" value="<?= $row['user_id'] ?>">

                <label class="icon">
                    <i class="ri-image-line"></i>
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
                </label>

                <input type="text" name="message" id="messageInput" placeholder="Type a message..." autocomplete="off" />

                <button class="sendButton" type="submit">Send</button>
                <div id="previewContainer" class="preview-box" style="display: none;"></div>
            </form>

        <?php else: ?>
            <div class="blocked-message">
                Bạn không thể nhắn tin vì đã bị chặn.
            </div>
        <?php endif; ?>


    </div>
</body>

</html>
<script src=" js/chat.js">
</script>