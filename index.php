<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/Login.css">
    <link rel="stylesheet" href="./css/Global.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="login_container">
        <div class="login_content">
            <h1>Login to your account.</h1>

            <form class="login_form" id="loginForm" method="POST" action="php/login_config.php"
                enctype="multipart/form-data">
                <div class="error_text"></div>
                <div class="login_box">
                    <input type="text" required name="email" placeholder=" " class="login_input" />
                    <label class="login_label">Email</label>
                    <i class="ri-user-line login_icon"></i>
                </div>

                <div class="login_box">
                    <input type="password" name="password" placeholder=" " class="login_input" id="password" required />
                    <label class="login_label">Password</label>
                    <i class="ri-lock-password-line login_icon"></i>
                    <i class="ri-eye-off-line toggle_password_icon" id="togglePassword"></i>
                </div>

                <a href="forgot-password.php" class="login_forgot">Forgot Password?</a>

                <button type="submit" class="login_button">Login</button>
            </form>

            <p class="login_switch">
                Don’t have an account? <a href="register.php">Create Account</a>
            </p>

            <div id="loginResult"></div>
        </div>
    </div>
    <script src="js/hide_pass.js"></script>
    <script src="js/login.js"></script>
</body>

</html>