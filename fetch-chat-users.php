<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    exit();
}

$user_id = $_SESSION['user_id'];

/*detect path prefix*/
$prefix = "";

if(isset($_SERVER['HTTP_REFERER'])){
    if(strpos($_SERVER['HTTP_REFERER'], "/admin/") !== false ||
       strpos($_SERVER['HTTP_REFERER'], "/technician/") !== false){
        $prefix = "../";
    }
}

if($_SESSION['role'] == 'user'){

    $chat_users = mysqli_query(
        $conn,
        "SELECT *
         FROM users
         WHERE role IN ('admin','technician')
         LIMIT 20"
    );

}else{

    $chat_users = mysqli_query(
        $conn,
        "SELECT *
         FROM users
         WHERE user_id != '$user_id'
         LIMIT 20"
    );

}

while($u = mysqli_fetch_assoc($chat_users)){

    $unread_query = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM messages
         WHERE sender_id='".$u['user_id']."'
         AND receiver_id='$user_id'
         AND is_read=0"
    );

    $unread = mysqli_fetch_assoc($unread_query)['total'];

    $image_path = !empty($u['profile_image'])
        ? $u['profile_image']
        : "uploads/profile/default.png";

    $profile_image = $prefix . $image_path;
?>

<div
    class="chat-user <?= $unread > 0 ? 'has-unread' : ''; ?>"
    data-user-id="<?= $u['user_id']; ?>"
    onclick="selectChat(
        <?= $u['user_id']; ?>,
        '<?= htmlspecialchars($u['username'], ENT_QUOTES); ?>',
        '<?= $profile_image; ?>',
        this
    )">

    <img src="<?= $profile_image; ?>">

    <div class="chat-user-info">

        <div class="chat-user-top">
            <h4><?= $u['username']; ?></h4>
            <span>Online</span>
        </div>

        <div class="chat-preview-row">

            <p class="chat-preview-text">
                <?= $unread > 0 ? 'New message received' : 'Click to start chat'; ?>
            </p>

            <?php if($unread > 0){ ?>
                <span class="chat-unread-badge">
                    <?= $unread; ?>
                </span>
            <?php } ?>

        </div>

    </div>

</div>

<?php } ?>