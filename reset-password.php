<?php
session_start();

include("includes/db.php");

if(
    !isset($_SESSION['reset_email']) ||
    !isset($_SESSION['otp_verified']) ||
    $_SESSION['otp_verified'] !== true
){
    header("Location: forgot-password.php");
    exit();
}

$email = $_SESSION['reset_email'];

$error_message = "";

if(isset($_POST['reset_password'])){

    $new_password = $_POST['new_password'] ?? "";
    $confirm_password = $_POST['confirm_password'] ?? "";

    if(strlen($new_password) < 8){

        $error_message = "Password must be at least 8 characters.";

    }elseif($new_password !== $confirm_password){

        $error_message = "Passwords do not match.";

    }else{

        $email_safe = mysqli_real_escape_string($conn, $email);

        $check_query = mysqli_query(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email='$email_safe'
             AND otp_verified='1'
             LIMIT 1"
        );

        if($check_query && mysqli_num_rows($check_query) > 0){

            $hashed_password = password_hash(
                $new_password,
                PASSWORD_DEFAULT
            );

            $hashed_password_safe = mysqli_real_escape_string(
                $conn,
                $hashed_password
            );

            $update_query = mysqli_query(
                $conn,
                "UPDATE users
                 SET
                    password='$hashed_password_safe',
                    otp_code=NULL,
                    otp_expiry=NULL,
                    otp_verified='0'
                 WHERE email='$email_safe'"
            );

            if($update_query){

                unset($_SESSION['reset_email']);
                unset($_SESSION['otp_verified']);
                unset($_SESSION['otp_last_sent']);

                $_SESSION['password_reset_success'] = true;

                header("Location: login.php");
                exit();

            }else{

                $error_message = "Failed to reset password. Please try again.";

            }

        }else{

            $error_message = "OTP verification is invalid. Please request a new OTP.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

        <title>Reset Password - ReTech Hub</title>

        <link
        rel="stylesheet"
        href="assets/css/password-reset.css">

        <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="login-container">

            <!-- LEFT SIDE -->
            <div class="left-panel">

            </div>

            <!-- RIGHT SIDE -->
            <div class="right-panel">

                <div class="login-card">

                    <!-- Back Button -->
                    <div class="back-login">

                        <a href="login.php">

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Login

                        </a>

                    </div>

                    <!-- Icon -->
                    <div class="reset-icon">

                        <i class="fa-solid fa-key"></i>

                    </div>

                    <h2 class="password-title">
                        Reset Password
                    </h2>

                    <p class="reset-description">
                        Enter a new password for your ReTech Hub account.
                        Make sure your password is secure and easy to remember.
                    </p>

                    <?php if($error_message != ""){ ?>

                        <div class="error-message">

                            <?= htmlspecialchars($error_message); ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <!-- New Password -->
                        <label for="newPassword">
                            New Password
                        </label>

                        <div class="password-field">

                            <input
                            type="password"
                            name="new_password"
                            id="newPassword"
                            minlength="8"
                            placeholder="Enter new password"
                            autocomplete="new-password"
                            required>

                            <button
                            type="button"
                            class="password-toggle"
                            aria-label="Show or hide new password"
                            onclick="togglePassword(
                                'newPassword',
                                'newPasswordIcon'
                            )">

                                <i
                                class="fa-solid fa-eye"
                                id="newPasswordIcon"></i>

                            </button>

                        </div>

                        <!-- Confirm Password -->
                        <label for="confirmPassword">
                            Confirm Password
                        </label>

                        <div class="password-field">

                            <input
                            type="password"
                            name="confirm_password"
                            id="confirmPassword"
                            minlength="8"
                            placeholder="Confirm new password"
                            autocomplete="new-password"
                            required>

                            <button
                            type="button"
                            class="password-toggle"
                            aria-label="Show or hide confirm password"
                            onclick="togglePassword(
                                'confirmPassword',
                                'confirmPasswordIcon'
                            )">

                                <i
                                class="fa-solid fa-eye"
                                id="confirmPasswordIcon"></i>

                            </button>

                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements">

                            <p>
                                <i class="fa-solid fa-circle-check"></i>

                                Password must contain at least 8 characters.
                            </p>

                        </div>

                        <!-- Submit Button -->
                        <button
                        type="submit"
                        name="reset_password"
                        class="login-btn">

                            <i class="fa-solid fa-key"></i>

                            Reset Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <script>
        function togglePassword(inputId, iconId){

            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if(input.type === "password"){

                input.type = "text";

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            }else{

                input.type = "password";

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            }
        }
        </script>

    </body>
</html>