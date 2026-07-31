<?php

session_start();

include("includes/db.php");

if(isset($_SESSION['pending_login_user_id'])){

    $user_id = (int) $_SESSION['pending_login_user_id'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users
         SET
            login_otp=NULL,
            login_otp_expiry=NULL
         WHERE user_id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

unset(
    $_SESSION['pending_login_user_id'],
    $_SESSION['login_otp_last_sent']
);

header("Location: login.php");
exit();
?>