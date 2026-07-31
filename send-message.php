<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = (int)$_POST['receiver_id'];

$message = "";

if(isset($_POST['message'])){
    $message = mysqli_real_escape_string($conn, $_POST['message']);
}

/* SEND TEXT MESSAGE */
if($message != ""){
    mysqli_query(
        $conn,
        "INSERT INTO messages
        (sender_id, receiver_id, message, image, is_read)
        VALUES
        ('$sender_id','$receiver_id','$message','',0)"
    );
}

/* SEND MULTIPLE IMAGES */
if(isset($_FILES['images'])){

    $folder = "uploads/messages/";

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    foreach($_FILES['images']['name'] as $key => $name){

        if($_FILES['images']['error'][$key] == 0){

            $file_name =
            time() . "_" . rand(1000,9999) . "_" . basename($name);

            $target = $folder . $file_name;

            move_uploaded_file(
                $_FILES['images']['tmp_name'][$key],
                $target
            );

            mysqli_query(
                $conn,
                "INSERT INTO messages
                (sender_id, receiver_id, message, image, is_read)
                VALUES
                ('$sender_id','$receiver_id','','$target',0)"
            );
        }
    }
}

/* SEND NOTIFICATION */
mysqli_query(
    $conn,
    "INSERT INTO notifications
    (user_id,title,message,type)
    VALUES
    (
        '$receiver_id',
        'New Message',
        'You received a new message.',
        'message'
    )"
);
?>