<?php

session_start();

include("includes/db.php");
include("includes/mail.php");

$message = "";

if(isset($_POST['register'])){

    $username = trim($_POST['username'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $password = $_POST['password'] ?? "";
    $confirm_password = $_POST['confirm_password'] ?? "";

    /*Basic validation*/

    if(
        $username === "" ||
        $email === "" ||
        $phone === "" ||
        $password === "" ||
        $confirm_password === ""
    ){
        $message = "Please complete all fields.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Please enter a valid email address.";
    }
    elseif(!preg_match('/^[0-9]{10,11}$/', $phone)){
        $message = "Phone number must contain 10 or 11 digits.";
    }
    elseif(strlen($password) < 8){
        $message = "Password must contain at least 8 characters.";
    }
    elseif($password !== $confirm_password){
        $message = "Passwords do not match.";
    }
    else{

        /*Check whether email already exists*/

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT user_id FROM users WHERE email = ? LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $email
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if(mysqli_num_rows($check_result) > 0){

            $message = "Email already exists.";

        }
        else{

            /*Generate 6-digit OTP*/

            $otp = (string)random_int(100000, 999999);

            /*Save registration information temporarily*/

            $_SESSION['register_data'] = [
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash(
                    $password,
                    PASSWORD_DEFAULT
                ),
                'otp' => $otp,
                'otp_expiry' => time() + 600
            ];

            /*Send OTP email*/

            $sent = sendRegisterOTP(
                $email,
                $otp
            );

            if($sent){

                header(
                    "Location: verify-register-otp.php"
                );

                exit();

            }
            else{

                unset($_SESSION['register_data']);

                $message = "Unable to send verification code. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>

    <meta charset="UTF-8">
    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Register | ReTech Hub</title>

    <link rel="stylesheet"
    href="assets/css/register.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    </head>

    <body>

        <div class="register-container">

            <!--LEFT-->

            <div class="left-panel">
            </div>

            <!--RIGHT-->

            <div class="right-panel">
                <div class="register-card">
                    <h2>Create an account</h2>
                    <?php if($message != ""){ ?>
                    <div class="error-box">
                        <?= htmlspecialchars($message); ?>
                    </div>
                    <?php } ?>

                    <form method="POST">
                        <label>Username</label>
                        <div class="input-group">
                            <i class="fa-regular fa-user"></i>
                            <input type="text" name="username" placeholder="Enter your username" required>

                        </div>

                        <label>Email Address</label>

                        <div class="input-group">

                            <i class="fa-regular fa-envelope"></i>

                            <input type="email" name="email" placeholder="Enter your email" required>

                        </div>

                        <label>Phone Number</label>

                        <div class="input-group">

                            <i class="fa-solid fa-phone"></i>

                            <input
                            type="text"
                            name="phone"
                            placeholder="Enter your phone number"
                            required
                            maxlength="11"
                            pattern="[0-9]{10,11}"
                            inputmode="numeric"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">

                        </div>

                        <label>Password</label>

                        <div class="input-group">

                            <i class="fa-solid fa-lock"></i>

                            <input
                            type="password"
                            name="password"
                            placeholder="Create your password"
                            required
                            minlength="8">

                        </div>

                        <label>Confirm Password</label>

                        <div class="input-group">

                            <i class="fa-solid fa-lock"></i>

                            <input
                            type="password"
                            name="confirm_password"
                            placeholder="Confirm your password"
                            required
                            minlength="8">

                        </div>

                        <div class="terms">

                            <input
                            type="checkbox"
                            required>

                            I agree to the
                            <span>
                                Terms & Conditions
                            </span>

                        </div>

                        <button
                        type="submit"
                        name="register"
                        class="register-btn">

                            Create Account

                        </button>

                    </form>

                    <p class="login-link">

                        Already have an account?

                        <a href="login.php">

                            Login Now

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </body>
</html>