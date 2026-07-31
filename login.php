<?php

session_start();

include("includes/db.php");
include("includes/mail.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

$error = "";

if(isset($_POST['login'])){

    /*read email and password*/
    $email = trim($_POST['email'] ?? "");
    $password = $_POST['password'] ?? "";

    if($email === "" || $password === ""){

        $error = "Please enter your email and password.";

    /*verify email format*/
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $error = "Please enter a valid email address.";

    }else{

        $stmt = mysqli_prepare(
            $conn,
            "SELECT
                user_id,
                username,
                email,
                password,
                role,
                profile_image
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if(!$stmt){

            $error = "Unable to process login request.";

        }else{

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            /*check if the email exists*/
            if($result && mysqli_num_rows($result) > 0){

                $user = mysqli_fetch_assoc($result);

                /*verify password*/
                if(password_verify(
                    $password,
                    $user['password']
                )){

                    /*generate 6-bit Login OTP*/
                    $login_otp = (string) random_int(
                        100000,
                        999999
                    );

                    /*verify login OTP expiry*/
                    $login_otp_expiry = date(
                        "Y-m-d H:i:s",
                        strtotime("+5 minutes")
                    );

                    $update_stmt = mysqli_prepare(
                        $conn,
                        "UPDATE users
                         SET
                            login_otp = ?,
                            login_otp_expiry = ?
                         WHERE user_id = ?"
                    );

                    if(!$update_stmt){

                        $error =
                            "Unable to generate login OTP.";

                    }else{

                        mysqli_stmt_bind_param(
                            $update_stmt,
                            "ssi",
                            $login_otp,
                            $login_otp_expiry,
                            $user['user_id']
                        );

                        if(mysqli_stmt_execute($update_stmt)){

                            /*send login OTP email*/
                            if(sendLoginOTP(
                                $user['email'],
                                $login_otp
                            )){

                                $_SESSION['pending_login_user_id'] = $user['user_id'];

                                /*record OTP sending time*/
                                $_SESSION['login_otp_last_sent'] = time();

                                /*direct to login OTP page*/
                                header(
                                    "Location: verify-login-otp.php"
                                );

                                exit();

                            }else{

                                /*delete OTP database when email sending fail*/
                                $clear_stmt = mysqli_prepare(
                                    $conn,
                                    "UPDATE users
                                     SET
                                        login_otp = NULL,
                                        login_otp_expiry = NULL
                                     WHERE user_id = ?"
                                );

                                if($clear_stmt){

                                    mysqli_stmt_bind_param(
                                        $clear_stmt,
                                        "i",
                                        $user['user_id']
                                    );

                                    mysqli_stmt_execute(
                                        $clear_stmt
                                    );

                                    mysqli_stmt_close(
                                        $clear_stmt
                                    );
                                }

                                $error =
                                    "Unable to send login OTP. "
                                    . "Please try again.";
                            }

                        }else{

                            $error = "Unable to save login OTP.";
                        }

                        mysqli_stmt_close($update_stmt);
                    }

                }else{

                    $error = "Wrong Password";
                }

            }else{

                $error = "Email Not Found";
            }

            mysqli_stmt_close($stmt);
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

        <title>Login | ReTech Hub</title>

        <link
        rel="stylesheet"
        href="assets/css/login.css">

        <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="login-container">

            <div class="left-panel">
            </div>

            <div class="right-panel">

                <div class="login-card">

                    <h2>Login</h2>

                    <?php
                    if(isset($_SESSION['password_reset_success'])){
                    ?>

                        <div class="success-message">

                            Password reset successfully.
                            Please login with your new password.

                        </div>

                    <?php

                        unset(
                            $_SESSION['password_reset_success']
                        );
                    }
                    ?>

                    <?php
                    if(isset($_SESSION['register_success'])){
                    ?>

                        <div class="success-message">

                            <?= htmlspecialchars($_SESSION['register_success']); ?>

                        </div>

                    <?php

                        unset(
                            $_SESSION['register_success']
                        );
                    }
                    ?>

                    <?php if($error !== ""){ ?>

                        <div class="error-message">

                            <?= htmlspecialchars($error); ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <label for="email">
                            Email Address
                        </label>

                        <div class="input-group">

                            <i class="fa-regular fa-envelope"></i>

                            <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?= htmlspecialchars(
                                $_POST['email'] ?? ''
                            ); ?>"
                            placeholder="Enter your email"
                            autocomplete="email"
                            required>

                        </div>

                        <label for="loginPassword">
                            Password
                        </label>

                        <div class="input-group">

                            <i class="fa-solid fa-lock"></i>

                            <input
                            type="password"
                            name="password"
                            id="loginPassword"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required>

                            <button
                            type="button"
                            class="password-toggle"
                            aria-label="Show or hide password"
                            onclick="togglePassword()">

                                <i
                                id="passwordIcon"
                                class="fa-regular fa-eye"></i>

                            </button>

                        </div>

                        <div class="options">

                            <a href="forgot-password.php">
                                Forgot Password?
                            </a>

                        </div>

                        <button type="submit" name="login" class="login-btn">

                            Login

                        </button>

                    </form>

                    <div class="divider">

                        <span>or continue with</span>

                    </div>

                    <button type="button" class="social-btn">

                        <i class="fab fa-google"></i>

                        Continue with Google

                    </button>

                    <button type="button" class="social-btn">

                        <i class="fab fa-facebook"></i>

                        Continue with Facebook

                    </button>

                    <p class="register">

                        Don't have an account?

                        <a href="register.php">
                            Register Now
                        </a>

                    </p>

                </div>

            </div>

        </div>

        <script>

        /*hidden password*/
        function togglePassword(){

            const password = document.getElementById("loginPassword");

            const icon = document.getElementById("passwordIcon");

            if(password.type === "password"){

                password.type = "text";

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            }else{

                password.type = "password";

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            }
        }

        </script>

    </body>
</html>