<?php $token = $_GET['token'] ?? ''; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Set New Password</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/global.css">
</head>

<body>
    <div class="login_container">
        <div class="login_content">
            <h1>Enter new password</h1>

            <form class="login_form" method="POST" action="save.php">

                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="login_box">
                    <input type="password" required name="password" placeholder=" " class="login_input" />
                    <label class="login_label">New password</label>
                </div>

                <button type="submit" class="login_button">Change Password</button>
            </form>
        </div>
    </div>
</body>

</html>