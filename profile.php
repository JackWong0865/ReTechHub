<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

/*Update user information*/
if(isset($_POST['update_profile'])){

    /*read user input*/
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    /*verify phone number*/
    if(!preg_match('/^[0-9]{10,11}$/', $phone)){
        $message = "Phone number must be 10 to 11 digits only.";
    }else{
        /*update user info*/
        mysqli_query($conn,
            "UPDATE users 
             SET username='$username',
                 email='$email',
                 phone='$phone'
             WHERE user_id='$user_id'"
        );

        /*Update Username in Session*/
        $_SESSION['username'] = $username;

        /*show update successful message*/
        $message = "Profile updated successfully!";
        }
}

/*update user avatar*/
if(isset($_POST['upload_avatar'])){

    if(!empty($_FILES['avatar']['name'])){

        $file_name = time() . "_" . $_FILES['avatar']['name'];
        $tmp_name = $_FILES['avatar']['tmp_name'];

        $folder = "uploads/profile/" . $file_name;

        move_uploaded_file($tmp_name, $folder);

        mysqli_query($conn,
            "UPDATE users
             SET profile_image='$folder'
             WHERE user_id='$user_id'"
        );

        $_SESSION['profile_image'] = $folder;

        $message = "Profile image updated successfully!";
    }
}

$user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'")
);

if($user['role'] == 'admin'){
    $backLink = "admin/admin.php";
}elseif($user['role'] == 'technician'){
    $backLink = "technician/dashboard.php";
}else{
    $backLink = "index.php";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>My Profile - ReTech Hub</title>
        <link rel="stylesheet" href="assets/css/profile.css">
    </head>

    <body>

        <div class="profile-page">

            <a href="<?= $backLink; ?>" class="back-btn">
                ← Back
            </a>

            <div class="profile-card">

                <h1>My Profile</h1>

                <?php if($message != ""){ ?>
                    <div class="message">
                        <?= $message; ?>
                    </div>
                <?php } ?>

                <div class="profile-top">

                    <img src="<?= $user['profile_image']; ?>" class="profile-avatar">

                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="avatar" accept="image/*" required>

                        <button type="submit" name="upload_avatar">
                            Upload Photo
                        </button>
                    </form>

                </div>

                <form method="POST">

                    <label>Username</label>
                    <input type="text" name="username" value="<?= $user['username']; ?>" required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?= $user['email']; ?>" required>

                    <label>Phone</label>
                    <input type="text" name="phone" value="<?= $user['phone']; ?>" pattern="[0-9]{10,11}" maxlength="11" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button type="submit" name="update_profile">
                        Save Changes
                    </button>
                    

                </form>

        </div>

    </div>

    </body>
</html>