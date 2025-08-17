    <?php
    include_once 'php/config.php';
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    ?>

    <div class="detail" style="display: <?php echo $display; ?>">
        <div class=" user">
            <img class="userimg" src="uploads/<?php echo htmlspecialchars($row['avatar']); ?>" alt="img_avatar">
            <span class="username"><?php echo htmlspecialchars($row['name']); ?></span>
        </div>

        <div class="info">
            <div class="search">
                <input type="text" id="searchInput" placeholder="search in chat" />
            </div>

            <div class="block_logout">
                <?php if ($isBlockedByOther): ?>
                    <div class="blocked-message">
                        Bạn đã bị người này chặn. Không thể nhắn tin hoặc tương tác.
                    </div>
                <?php endif; ?>

                <?php if ($friend_status !== 'block' || $isBlocker): ?>
                    <form method="post" action="php/block/block.php">
                        <input type="hidden" name="receiver_id" value="<?= $row['user_id'] ?>">
                        <input type="hidden" name="action"
                            value="<?= $friend_status === 'block' && $isBlocker ? 'unblock' : 'block' ?>">
                        <button type="submit" class="red-btn">
                            <?= $friend_status === 'block' && $isBlocker ? 'Unblock' : 'Block' ?> User
                        </button>
                    </form>


                <?php endif; ?>

            </div>

        </div>
    </div>
    <script src="js/detail.js"></script>