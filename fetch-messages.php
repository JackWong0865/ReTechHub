<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    exit();
}

$user_id = $_SESSION['user_id'];
$receiver_id = (int)$_GET['receiver_id'];

/*path prefix*/
$prefix = "";

if(isset($_SERVER['HTTP_REFERER'])){
    if(strpos($_SERVER['HTTP_REFERER'], "/admin/") !== false ||
       strpos($_SERVER['HTTP_REFERER'], "/technician/") !== false){
        $prefix = "../";
    }
}

/*mark as read*/
mysqli_query(
    $conn,
    "UPDATE messages
     SET is_read=1
     WHERE sender_id='$receiver_id'
     AND receiver_id='$user_id'"
);

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM messages
     WHERE 
     (sender_id='$user_id' AND receiver_id='$receiver_id')
     OR
     (sender_id='$receiver_id' AND receiver_id='$user_id')
     ORDER BY created_at ASC"
);

while($row = mysqli_fetch_assoc($query)){

    $class = $row['sender_id'] == $user_id ? "sent" : "received";

    echo "<div class='message $class'>";
    echo "<div class='message-bubble'>";

    /*text message*/
    if(!empty($row['message'])){
        echo "<p>{$row['message']}</p>";
    }

    /*image message*/
    if(!empty($row['image'])){

        echo "
        <div class='chat-image-wrap'>
            <img
            src='".$prefix.$row['image']."'
            class='chat-image'
            onclick='openImage(this.src)'>
        </div>";
    }

    echo "<span>{$row['created_at']}</span>";

    echo "</div>";
    echo "</div>";
}
?>