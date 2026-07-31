<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/*get current login user id*/
$user_id = $_SESSION['user_id'];
$message = "";
$messageType = "";

/*check current password*/
$stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

/*get check result*/
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

/*check if the user exists*/
if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['change_password'])) {

    /*get input info*/
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    /*verify current password*/
    if (!password_verify($current_password, $user['password'])) {

        $message = "Current password is incorrect.";
        $messageType = "error";

    /*check password length*/
    } elseif (strlen($new_password) < 8) {

        $message = "Password must be at least 8 characters.";
        $messageType = "error";

    /*check confirm password*/
    } elseif ($new_password != $confirm_password) {

        $message = "New passwords do not match.";
        $messageType = "error";

    /*check if it is the same as the old password.*/
    } elseif (password_verify($new_password, $user['password'])) {

        $message = "New password cannot be the same as the current password.";
        $messageType = "error";

    } else {

        /*encrypt new password*/
        $newHash = password_hash($new_password, PASSWORD_DEFAULT);

        /*update database*/
        $update = mysqli_prepare(
            $conn,
            "UPDATE users
             SET password=?
             WHERE user_id=?"
        );

        mysqli_stmt_bind_param(
            $update,
            "si",
            $newHash,
            $user_id
        );

        if (mysqli_stmt_execute($update)) {

            $message = "Password changed successfully.";
            $messageType = "success";

        } else {

            $message = "Unable to change password.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password - ReTech Hub</title>

    <link rel="stylesheet" href="assets/css/password-reset.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <!--page code-->
        <div class="login-container">

            <div class="left-panel">

            </div>

            <div class="right-panel">

                <div class="login-card">

                    <a href="javascript:history.back()" class="back-login">

                        <i class="fa-solid fa-arrow-left"></i>

                        Back

                    </a>

                    <div class="reset-icon">

                        <i class="fa-solid fa-key"></i>

                    </div>

                    <h2>Change Password</h2>

                    <p class="reset-description">

                        Enter your current password and choose a new password.

                    </p>

                    <?php if($message!=""){ ?>

                        <div class="<?= $messageType=="success" ? "success-message" : "error-message"; ?>">

                            <?= htmlspecialchars($message); ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <label>Current Password</label>
                        <div class="password-field">

                            <input type="password" id="currentPassword" name="current_password" required>

                            <button type="button" class="password-toggle" onclick="togglePassword('currentPassword','icon1')">
                                <i id="icon1" class="fa-regular fa-eye"></i>
                            </button>

                        </div>

                        <label>New Password</label>

                        <div class="password-field">

                            <input type="password" id="newPassword" name="new_password" minlength="8" required>

                            <button type="button" class="password-toggle" onclick="togglePassword('newPassword','icon2')">

                                <i id="icon2" class="fa-regular fa-eye"></i>

                            </button>

                        </div>

                        <label>Confirm Password</label>

                        <div class="password-field">

                            <input type="password" id="confirmPassword" name="confirm_password" minlength="8" required>

                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword','icon3')">

                                <i id="icon3" class="fa-regular fa-eye"></i>

                            </button>

                        </div>

                        <p class="password-note">
                            Password must contain at least 8 characters.
                        </p>

                        <button type="submit" name="change_password" class="login-btn">
                            Change Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!--hidden password-->
        <script>

        function togglePassword(inputId, iconId){

            const input=document.getElementById(inputId);

            const icon=document.getElementById(iconId);

            if(input.type==="password"){

                input.type="text";

                icon.classList.replace(
                    "fa-eye",
                    "fa-eye-slash"
                );

            }else{

                input.type="password";

                icon.classList.replace(
                    "fa-eye-slash",
                    "fa-eye"
                );

            }

        }

        </script>

    </body>
</html>