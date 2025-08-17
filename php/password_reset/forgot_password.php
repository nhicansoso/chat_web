<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../css/global.css">
</head>

<body>
    <div class="login_container">
        <div class="login_content">
            <h1>Reset your password</h1>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'sent'): ?>
                <p style="color: green;margin-bottom :20px;">Reset link has been sent to your email.</p>
            <?php elseif (isset($_GET['error'])): ?>
                <p style="color: red;">
                    <?php
                    if ($_GET['error'] === 'notfound') echo "Email not found.";
                    elseif ($_GET['error'] === 'empty') echo "Email is required.";
                    elseif ($_GET['error'] === 'mailfail') echo "Failed to send email.";
                    ?>
                </p>
            <?php endif; ?>

            <form class="login_form" method="POST" action="send.php">
                <div class="login_box">
                    <input type="email" required name="email" placeholder=" " class="login_input" />
                    <label class="login_label">Enter your email</label>
                </div>

                <button type="submit" class="login_button">Send Reset Link</button>
            </form>

            <p class="login_switch">
                Back to <a href="../../index.php">Login</a>
            </p>
        </div>
    </div>
</body>

</html>