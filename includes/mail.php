<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

/*forget password*/
function sendOTP($email,$otp){

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host='smtp.gmail.com';

        $mail->SMTPAuth=true;

        $mail->Username='n75987403@gmail.com';

        $mail->Password='flsk zkab elwo anfz';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port=587;

        $mail->setFrom( 'retechhubofficial@gmail.com', 'ReTech Hub');

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject='Password Reset OTP';

        $mail->Body="

        <h2>ReTech Hub Password Reset</h2>

        <p>Your OTP is:</p>

        <h1>$otp</h1>

        <p>This code expires in 10 minutes.</p>

        ";

        return $mail->send();

    }catch(Exception $e){

        return false;

    }

}

/*login mail*/

function sendLoginOTP($email, $otp)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'n75987403@gmail.com';
        $mail->Password = 'flsk zkab elwo anfz';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
            'retechhubofficial@gmail.com',
            'ReTech Hub'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject =
            'ReTech Hub Login Verification';

        $mail->Body = "
            <div style='font-family:Arial,sans-serif;
                        max-width:520px;
                        margin:auto;
                        padding:25px;
                        border:1px solid #ddd;
                        border-radius:12px;'>

                <h2 style='color:#d32f2f;'>
                    ReTech Hub Login Verification
                </h2>

                <p>
                    A login attempt was made for your
                    ReTech Hub account.
                </p>

                <p>Your verification code is:</p>

                <div style='font-size:36px;
                            font-weight:bold;
                            letter-spacing:8px;
                            color:#d32f2f;
                            text-align:center;
                            margin:25px 0;'>

                    {$otp}

                </div>

                <p>
                    This OTP will expire in 5 minutes.
                </p>

                <p style='color:#777;font-size:13px;'>
                    If you did not attempt to log in,
                    please ignore this email.
                </p>

            </div>
        ";

        $mail->AltBody =
            "Your ReTech Hub login OTP is: {$otp}. "
            . "This OTP expires in 5 minutes.";

        return $mail->send();

    } catch (Exception $e) {

        return false;
    }
}

/*register*/

function sendRegisterOTP($email, $otp){

    $mail = new PHPMailer(true);

    try{

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'n75987403@gmail.com';
        $mail->Password = 'flsk zkab elwo anfz';

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom(
            'n75987403@gmail.com',
            'ReTech Hub'
        );

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject =
            'Verify Your ReTech Hub Account';

        $mail->Body = "
            <div style='font-family:Arial,sans-serif'>
                <h2>ReTech Hub Email Verification</h2>

                <p>
                    Use the following verification code
                    to complete your registration:
                </p>

                <h1 style='letter-spacing:6px;color:#e60000'>
                    $otp
                </h1>

                <p>
                    This verification code will expire
                    in 10 minutes.
                </p>

                <p>
                    Do not share this code with anyone.
                </p>
            </div>
        ";

        $mail->AltBody =
            "Your ReTech Hub verification code is: $otp. "
            . "This code expires in 10 minutes.";

        return $mail->send();

    }
    catch(Exception $e){

        return false;
    }
}