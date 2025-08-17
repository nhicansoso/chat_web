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

<div class="userinfo">
    <div class="user">
        <img class="userimg" src="uploads/<?php echo htmlspecialchars($row['avatar']); ?>" alt="img_avatar">
        <span class="username"><?php echo htmlspecialchars($row['name']); ?></span>
    </div>
    <form action="./php/logout.php" method="post">
        <button type="submit" class="red-btn" name="logout">Logout</button>
    </form>
</div>