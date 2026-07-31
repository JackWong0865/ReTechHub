<?php

session_start();

include("includes/db.php");
include("includes/mail.php");

date_default_timezone_set("Asia/Kuala_Lumpur");

if(!isset($_SESSION['pending_login_user_id'])){

    header("Location: login.php");
    exit();
}

$user_id =
    (int) $_SESSION['pending_login_user_id'];

$error_message = "";
$success_message = "";

$cooldown_seconds = 60;

$last_sent =
    $_SESSION['login_otp_last_sent'] ?? 0;

$remaining_seconds = max(
    0,
    $cooldown_seconds - (time() - $last_sent)
);

/*get user info*/

$user_stmt = mysqli_prepare(
    $conn,
    "SELECT
        user_id,
        username,
        email,
        role,
        profile_image
     FROM users
     WHERE user_id=?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($user_stmt);

$user_result = mysqli_stmt_get_result($user_stmt);

if(!$user_result ||
   mysqli_num_rows($user_result) === 0){

    unset($_SESSION['pending_login_user_id']);

    header("Location: login.php");
    exit();
}

$user = mysqli_fetch_assoc($user_result);

/*Resend Login OTP*/

if(isset($_POST['resend_otp'])){

    $last_sent =
        $_SESSION['login_otp_last_sent'] ?? 0;

    $remaining_seconds = max(
        0,
        $cooldown_seconds -
        (time() - $last_sent)
    );

    if($remaining_seconds > 0){

        $error_message =
            "Please wait "
            . $remaining_seconds
            . " seconds before requesting another OTP.";

    }else{

        $new_otp = (string) random_int(100000, 999999);

        $new_expiry = date(
            "Y-m-d H:i:s",
            strtotime("+5 minutes")
        );

        $update_stmt = mysqli_prepare(
            $conn,
            "UPDATE users
             SET
                login_otp=?,
                login_otp_expiry=?
             WHERE user_id=?"
        );

        mysqli_stmt_bind_param(
            $update_stmt,
            "ssi",
            $new_otp,
            $new_expiry,
            $user_id
        );

        if(mysqli_stmt_execute($update_stmt)){

            if(sendLoginOTP(
                $user['email'],
                $new_otp
            )){

                $_SESSION['login_otp_last_sent'] = time();

                $remaining_seconds = $cooldown_seconds;

                $success_message = "A new login OTP has been sent.";

            }else{

                $error_message = "Unable to send a new OTP.";
            }

        }else{

            $error_message = "Unable to update the OTP.";
        }

        mysqli_stmt_close($update_stmt);
    }
}

/*Verify Login OTP*/

if(isset($_POST['verify_login_otp'])){

    $otp = trim($_POST['otp'] ?? "");

    if(!preg_match('/^[0-9]{6}$/', $otp)){

        $error_message =
            "Please enter a valid 6-digit OTP.";

    }else{

        $verify_stmt = mysqli_prepare(
            $conn,
            "SELECT
                user_id,
                username,
                email,
                role,
                profile_image
             FROM users
             WHERE
                user_id=?
                AND login_otp=?
                AND login_otp_expiry IS NOT NULL
                AND login_otp_expiry > NOW()
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $verify_stmt,
            "is",
            $user_id,
            $otp
        );

        mysqli_stmt_execute($verify_stmt);

        $verify_result = mysqli_stmt_get_result($verify_stmt);

        if($verify_result &&
           mysqli_num_rows($verify_result) > 0){

            $verified_user = mysqli_fetch_assoc($verify_result);

            /*Clear used OTPs*/

            $clear_stmt = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET
                    login_otp=NULL,
                    login_otp_expiry=NULL
                 WHERE user_id=?"
            );

            mysqli_stmt_bind_param(
                $clear_stmt,
                "i",
                $user_id
            );

            mysqli_stmt_execute($clear_stmt);
            mysqli_stmt_close($clear_stmt);

            /*Prevent Session Fixation*/

            session_regenerate_id(true);

            /*Formal establishment of login session*/

            $_SESSION['user_id'] = $verified_user['user_id'];

            $_SESSION['username'] = $verified_user['username'];

            $_SESSION['role'] = $verified_user['role'];

            $_SESSION['profile_image'] = $verified_user['profile_image'];

            unset(
                $_SESSION['pending_login_user_id'],
                $_SESSION['login_otp_last_sent']
            );

            /*Redirecting to the page based on the character*/

            if($verified_user['role'] === 'admin'){

                header(
                    "Location: admin/admin.php"
                );

            }elseif(
                $verified_user['role'] ===
                'technician'
            ){

                header(
                    "Location: technician/dashboard.php"
                );

            }else{

                header(
                    "Location: index.php"
                );
            }

            exit();

        }else{

            $error_message = "Invalid or expired login OTP.";
        }

        mysqli_stmt_close($verify_stmt);
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

        <title>Login Verification - ReTech Hub</title>

        <link
        rel="stylesheet"
        href="assets/css/password-reset.css">

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

                    <div class="back-login">

                        <a href="cancel-login-otp.php">

                            <i class="fa-solid fa-arrow-left"></i>

                            Back to Login

                        </a>

                    </div>

                    <div class="reset-icon">

                        <i class="fa-solid fa-shield-halved"></i>

                    </div>

                    <h2>Login Verification</h2>

                    <p class="reset-description">

                        We sent a 6-digit verification code to

                        <br>

                        <strong>
                            <?= htmlspecialchars($user['email']); ?>
                        </strong>

                        <br><br>

                        The OTP expires after 5 minutes.

                    </p>

                    <?php if($error_message !== ""){ ?>

                        <div class="error-message">

                            <?= htmlspecialchars($error_message); ?>

                        </div>

                    <?php } ?>

                    <?php if($success_message !== ""){ ?>

                        <div class="success-message">

                            <?= htmlspecialchars($success_message); ?>

                        </div>

                    <?php } ?>

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

                        <button type="submit" name="verify_login_otp" class="login-btn">

                            <i class="fa-solid fa-shield-halved"></i>

                            Verify and Login

                        </button>

                    </form>

                    <form method="POST" class="resend-area">

                        <button
                        type="submit"
                        name="resend_otp"
                        id="resendButton"
                        class="resend-btn"
                        <?= $remaining_seconds > 0
                            ? "disabled"
                            : ""; ?>>

                            <i class="fa-solid fa-rotate-right"></i>

                            <span id="resendText">

                                <?php if(
                                    $remaining_seconds > 0
                                ){ ?>

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

        const resendButton = document.getElementById("resendButton");

        const resendText = document.getElementById("resendText");

        let remainingSeconds =
            <?= (int) $remaining_seconds; ?>;

        if(remainingSeconds > 0){

            resendButton.disabled = true;

            const countdownTimer = setInterval(function(){

                remainingSeconds--;

                if(remainingSeconds > 0){

                    resendText.textContent =
                        "Resend OTP in "
                        + remainingSeconds
                        + "s";

                }else{

                    clearInterval(countdownTimer);

                    resendButton.disabled = false;

                    resendText.textContent =
                        "Resend OTP";
                }

            }, 1000);
        }

        </script>

    </body>
</html>