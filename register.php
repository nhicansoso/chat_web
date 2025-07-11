<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="./css/register.css">
    <link rel="stylesheet" href="./css/Global.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>

<body>
    <div class="register_container">
        <section class="register_content">
            <h1>Create your account.</h1>
            <form method="POST" action="php/register_config.php" enctype="multipart/form-data" class="register_form">
                <div class="error_text"></div>

                <div class="register_box">
                    <input name="username" type="text" required placeholder=" " class="register_input" />
                    <label class="register_label">Username</label>
                    <i class="ri-user-line register_icon"></i>
                </div>

                <div class="register_box">
                    <input name="email" type="email" required placeholder=" " class="register_input" />
                    <label class="register_label">Email</label>
                    <i class="ri-mail-line register_icon"></i>
                </div>

                <div class="register_box">
                    <input name="password" type="password" required placeholder=" " class="register_input"
                        autocomplete="new-password" />
                    <label class="register_label">Password</label>
                    <i class="ri-lock-password-line register_icon"></i>
                </div>

                <div class="register_box">
                    <label for="avatar" class="upload_wrapper" id="avatarLabel">
                        <img id="avatarImg" class="register_avatar_img" src="uploads/default_avatar.png" alt="" />
                        <span id="avatarText">Click to choose avatar</span>
                        <input type="file" name="avatar" id="avatar" accept="image/*" style="display: none;" />
                    </label>
                </div>

                <button type="submit" class="register_button">Register</button>
            </form>

            <p class="register_switch">
                Already have an account? <a href="index.php">Login</a>
            </p>
        </section>
    </div>
</body>
<script src="js/preview_img.js"></script>
<script src="js/register.js"></script>

</html>