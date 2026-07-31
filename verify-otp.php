<?php
session_start();

include("includes/db.php");
include("includes/mail.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

if(!isset($_SESSION['reset_email'])){
    header("Location: forgot-password.php");
    exit();
}

$email = $_SESSION['reset_email'];

$error_message = "";
$success_message = "";

$cooldown_seconds = 60;

$last_sent = $_SESSION['otp_last_sent'] ?? 0;

$remaining_seconds = max(
    0,
    $cooldown_seconds - (time() - $last_sent)
);

/*Resend OTP*/

if(isset($_POST['resend_otp'])){

    $last_sent = $_SESSION['otp_last_sent'] ?? 0;

    $remaining_seconds = max(
        0,
        $cooldown_seconds - (time() - $last_sent)
    );

    if($remaining_seconds > 0){

        $error_message =
            "Please wait " . $remaining_seconds .
            " seconds before requesting another OTP.";

    }else{

        $new_otp = random_int(100000, 999999);

        $expiry = date(
            "Y-m-d H:i:s",
            strtotime("+10 minutes")
        );

        $email_safe = mysqli_real_escape_string($conn, $email);

        $update_query = mysqli_query(
            $conn,
            "UPDATE users
             SET
                otp_code='$new_otp',
                otp_expiry='$expiry',
                otp_verified='0'
             WHERE email='$email_safe'"
        );

        if($update_query){

            if(sendOTP($email, $new_otp)){

                $_SESSION['otp_last_sent'] = time();

                $remaining_seconds = $cooldown_seconds;

                $success_message = "A new OTP has been sent to your email.";

            }else{

                $error_message = "Failed to resend OTP. Please try again.";

            }

        }else{

            $error_message = "Unable to update OTP information.";

        }
    }
}

/*Verify OTP*/

if(isset($_POST['verify_otp'])){

    $otp = trim($_POST['otp'] ?? "");

    if(!preg_match('/^[0-9]{6}$/', $otp)){

        $error_message = "Please enter a valid 6-digit OTP.";

    }else{

        $email_safe = mysqli_real_escape_string($conn, $email);
        $otp_safe = mysqli_real_escape_string($conn, $otp);

        $query = mysqli_query(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email='$email_safe'
             AND otp_code='$otp_safe'
             AND otp_expiry IS NOT NULL
             AND otp_expiry > NOW()
             LIMIT 1"
        );

        if($query && mysqli_num_rows($query) > 0){

            mysqli_query(
                $conn,
                "UPDATE users
                 SET otp_verified='1'
                 WHERE email='$email_safe'"
            );

            $_SESSION['otp_verified'] = true;

            header("Location: reset-password.php");
            exit();

        }else{

            $error_message = "Invalid or expired OTP.";

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

        <title>Verify OTP - ReTech Hub</title>

        <link rel="stylesheet" href="assets/css/password-reset.css">

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

                        <a href="forgot-password.php">

                            <i class="fa-solid fa-arrow-left"></i>

                            Back

                        </a>

                    </div>

                    <!-- Icon -->
                    <div class="reset-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>

                    <h2>Verify OTP</h2>

                    <p class="reset-description">

                        We have sent a 6-digit OTP to

                        <br>

                        <strong>
                            <?= htmlspecialchars($email); ?>
                        </strong>

                        <br><br>

                        Enter the verification code below.
                        The OTP will expire after 10 minutes.

                    </p>

                    <?php if($error_message != ""){ ?>

                        <div class="error-message">

                            <?= htmlspecialchars($error_message); ?>

                        </div>

                    <?php } ?>

                    <?php if($success_message != ""){ ?>

                        <div class="success-message">

                            <?= htmlspecialchars($success_message); ?>

                        </div>

                    <?php } ?>

                    <!-- Verify OTP Form -->
                    <form method="POST">

                        <label for="otp">
                            One-Time Password
                        </label>

                        <div class="input-group">

                            <i class="fa-solid fa-key"></i>

                            <input
                            type="text"
                            name="otp"
                            id="otp"
                            class="otp-input"
                            maxlength="6"
                            minlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            placeholder="000000"
                            required>

                        </div>

                        <button
                        class="login-btn"
                        type="submit"
                        name="verify_otp">

                            <i class="fa-solid fa-shield-halved"></i>

                            Verify OTP

                        </button>

                    </form>

                    <!-- Resend OTP Form -->
                    <form method="POST" class="resend-area">

                        <button
                        type="submit"
                        name="resend_otp"
                        id="resendButton"
                        class="resend-btn"
                        <?= $remaining_seconds > 0 ? "disabled" : ""; ?>>

                            <i class="fa-solid fa-rotate-right"></i>

                            <span id="resendText">

                                <?php if($remaining_seconds > 0){ ?>

                                    Resend OTP in
                                    <?= $remaining_seconds; ?>s

                                <?php }else{ ?>

                                    Resend OTP

                                <?php } ?>

                            </span>

                        </button>

                    </form>

                    <p class="otp-note">

                        Didn't receive the email?
                        Check your spam folder or resend the OTP.

                    </p>

                </div>

            </div>

        </div>

        <script>
        const resendButton =
            document.getElementById("resendButton");

        const resendText =
            document.getElementById("resendText");

        let remainingSeconds =
            <?= (int)$remaining_seconds; ?>;

        if(remainingSeconds > 0){

            resendButton.disabled = true;

            const countdownTimer = setInterval(function(){

                remainingSeconds--;

                if(remainingSeconds > 0){

                    resendText.textContent =
                        "Resend OTP in " +
                        remainingSeconds +
                        "s";

                }else{

                    clearInterval(countdownTimer);

                    resendButton.disabled = false;

                    resendText.textContent =
                        "Resend OTP";

                }

            },1000);
        }
        </script>

    </body>
</html>