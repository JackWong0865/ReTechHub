<?php

session_start();

include("includes/db.php");

$message = "";

if(!isset($_SESSION['register_data'])){

    header("Location: register.php");
    exit();
}

$register_data = $_SESSION['register_data'];

if(isset($_POST['verify_otp'])){

    $entered_otp = trim(
        $_POST['otp'] ?? ""
    );

    if($entered_otp === ""){

        $message = "Please enter the verification code.";

    }
    elseif(time() > $register_data['otp_expiry']){

        unset($_SESSION['register_data']);

        $message = "The verification code has expired. Please register again.";

    }
    elseif(!hash_equals(
        $register_data['otp'],
        $entered_otp
    )){

        $message = "Invalid verification code.";

    }
    else{

        $username = $register_data['username'];
        $email = $register_data['email'];
        $phone = $register_data['phone'];
        $hashed_password = $register_data['password'];

        /* Check email again before inserting */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if(mysqli_num_rows($check_result) > 0){

            unset($_SESSION['register_data']);

            $message = "This email has already been registered.";

        }
        else{

            $insert_stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (
                    username,
                    email,
                    phone,
                    password
                )
                VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert_stmt,
                "ssss",
                $username,
                $email,
                $phone,
                $hashed_password
            );

            if(mysqli_stmt_execute($insert_stmt)){

                /* Notify Admin */

                $admin_id = 1;

                $notification_title =
                    "New User Registered";

                $notification_message =
                    "A new user has registered: "
                    . $username . ".";

                $notification_type = "user";

                $notification_stmt =
                    mysqli_prepare(
                        $conn,
                        "INSERT INTO notifications
                        (
                            user_id,
                            title,
                            message,
                            type
                        )
                        VALUES (?, ?, ?, ?)"
                    );

                mysqli_stmt_bind_param(
                    $notification_stmt,
                    "isss",
                    $admin_id,
                    $notification_title,
                    $notification_message,
                    $notification_type
                );

                mysqli_stmt_execute(
                    $notification_stmt
                );

                unset($_SESSION['register_data']);

                $_SESSION['register_success'] =
                    "Account created successfully. Please log in.";

                header(
                    "Location: login.php?success=1"
                );

                exit();

            }
            else{

                $message = "Unable to create the account. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Verify Registration | ReTech Hub</title>

        <link rel="stylesheet" href="assets/css/register.css">

    </head>

    <body>

        <div class="register-container">

            <div class="left-panel"></div>

            <div class="right-panel">

                <div class="register-card">

                    <h2>Verify Your Email</h2>

                    <p>
                        A 6-digit verification code was sent to
                        <strong>
                            <?= htmlspecialchars(
                                $register_data['email']
                            ); ?>
                        </strong>
                    </p>

                    <?php if($message !== ""){ ?>

                        <div class="error-box">
                            <?= htmlspecialchars($message); ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <label>Verification Code</label>

                        <div class="input-group">

                            <input
                            type="text"
                            name="otp"
                            placeholder="Enter 6-digit code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                            required>

                        </div>

                        <button type="submit"  name="verify_otp" class="register-btn">

                            Verify Account

                        </button>

                    </form>

                    <p class="login-link">

                        Entered the wrong email?

                        <a href="register.php">
                            Register Again
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </body>
</html>