<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include_once './php/config.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<div class="detail">
    <div class="user">
        <img class="userimg" src="uploads/<?php echo htmlspecialchars($row['avatar']); ?>" alt="img_avatar">
        <span class="username"><?php echo htmlspecialchars($row['name']); ?></span>
    </div>

    <div class="info">
        <div class="search">
            <input type="text" placeholder="search in chat" />
            <i class="ri-search-2-line"></i>
        </div>

        <div class="option">
            <div class="title">
                <span>Chat Settings</span>
                <i class="ri-arrow-drop-up-line"></i>
            </div>

            <div class="title">
                <span>Shared Photos</span>
                <i class="ri-arrow-drop-up-line"></i>
            </div>

            <div class="photos">
                <div class="photoItem">
                    <div class="photoDetail">
                        <img src="./background.jpg" />
                        <span>photo_name</span>
                    </div>
                    <i class="ri-download-line"></i>
                </div>
            </div>

            <div class="photos">
                <div class="photoItem">
                    <div class="photoDetail">
                        <img src="./background.jpg" />
                        <span>photo_name</span>
                    </div>
                    <i class="ri-download-line"></i>
                </div>
            </div>

            <div class="photos">
                <div class="photoItem">
                    <div class="photoDetail">
                        <img src="./background.jpg" />
                        <span>photo_name</span>
                    </div>
                    <i class="ri-download-line"></i>
                </div>
            </div>

            <div class="title">
                <span>Share file</span>
                <i class="ri-arrow-drop-up-line"></i>
            </div>

            <div class="title">
                <span>Privacy and help</span>
                <i class="ri-arrow-drop-up-line"></i>
            </div>
        </div>

        <button>Block User</button>
        <form action="./php/logout.php" method="post">
            <button type="submit" name="logout">Logout</button>
        </form>

    </div>
</div>