<?php
session_start();

include("includes/db.php");
include("includes/mail.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

$message = "";

if(isset($_POST['send_otp'])){

    /*get user enter's email*/
    $email = trim($_POST['email'] ?? "");

    /*verify email format*/
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $message = "Please enter a valid email address.";

    }else{

        $email_safe = mysqli_real_escape_string($conn, $email);

        /*check if the email exists*/
        $check = mysqli_query(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email='$email_safe'
             LIMIT 1"
        );

        if($check && mysqli_num_rows($check) > 0){

            /*generate OTP*/
            $otp = random_int(100000, 999999);

            /*setting OTP expired date*/
            $expiry = date(
                "Y-m-d H:i:s",
                strtotime("+10 minutes")
            );

            /*update OTP to db*/
            $update = mysqli_query(
                $conn,
                "UPDATE users
                 SET
                    otp_code='$otp',
                    otp_expiry='$expiry',
                    otp_verified='0'
                 WHERE email='$email_safe'"
            );

            if($update){

                /**send OTP email*/
                if(sendOTP($email, $otp)){

                    $_SESSION['reset_email'] = $email;
                    $_SESSION['otp_last_sent'] = time();

                    /*direct to OTP verification page*/
                    header("Location: verify-otp.php");
                    exit();

                }else{
                    $message = "Failed to send OTP email. Please try again.";
                }

            }else{
                $message = "Unable to generate OTP. Please try again.";
            }

        }else{
            $message = "Email not found.";
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

        <title>Forgot Password | ReTech Hub</title>

        <link
        rel="stylesheet"
        href="assets/css/forgot-password.css">

        <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="login-container">

            <!--left side-->
            <div class="left-panel">
            </div>

            <!--right side-->
            <div class="right-panel">
                

                <div class="login-card forgot-card">

                    <div class="back-login">

                        <a href="login.php">

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Login

                        </a>

                    </div>

                    <div class="forgot-icon">

                        <i class="fa-solid fa-key"></i>

                    </div>

                    <h2>Forgot Password?</h2>

                    <p class="forgot-description">

                        Enter the email address associated with your account.
                        We will send you a 6-digit OTP to reset your password.

                    </p>

                    <?php if($message != ""){ ?>

                        <div class="error-message">

                            <?= htmlspecialchars($message); ?>

                        </div>

                    <?php } ?>

                    <form method="POST">

                        <label>Email Address</label>

                        <div class="input-group">

                            <i class="fa-regular fa-envelope"></i>
                            <input type="email" name="email" placeholder="Enter your registered email" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>" autocomplete="email" required>

                        </div>

                        <button type="submit" name="send_otp" class="login-btn">

                            <i class="fa-solid fa-paper-plane"></i>

                            Send OTP

                        </button>

                    </form>

                    <div class="security-note">

                        <i class="fa-solid fa-shield-halved"></i>

                        <span>
                            The OTP will expire after 10 minutes.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </body>
</html>