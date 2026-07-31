<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'technician'){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$chat_users = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE user_id != '$user_id'
     LIMIT 20"
);

$first_user = mysqli_fetch_assoc($chat_users);
$receiver_id = $first_user ? $first_user['user_id'] : 0;

mysqli_data_seek($chat_users, 0);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Technician Messages - ReTech Hub</title>

        <link rel="stylesheet" href="../assets/css/technician.css">
        <link rel="stylesheet" href="../assets/css/messages.css">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>

    <body>

        <div class="tech-layout">

            <!--side bar-->
            <aside class="tech-sidebar">

                <img src="../assets/images/logo.png" class="tech-logo">

                <a href="dashboard.php">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

                <a href="assigned-repairs.php">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    Assigned Repairs
                </a>

                <a href="repair-requests.php">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Repair Requests
                </a>

                <a href="repair-history.php">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Repair History
                </a>

                <a href="messages.php" class="active">
                    <i class="fa-solid fa-message"></i>
                    Messages
                </a>

                <a href="../logout.php" class="logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>

            </aside>

            <!--page code-->
            <main class="tech-main">

                <div class="message-layout message-inside-dashboard">
                    <!--chat side bar-->
                    <div class="chat-sidebar">

                        <div class="chat-sidebar-top">

                            <a href="dashboard.php" class="back-message-btn">
                                <i class="fa-solid fa-arrow-left"></i>
                            </a>

                            <h2>Messages</h2>

                            <h2></h2>

                        </div>

                        <div class="chat-user-list" id="chatUserList">

                            <?php while($u = mysqli_fetch_assoc($chat_users)){ ?>

                                <?php
                                $profile_image = !empty($u['profile_image'])
                                    ? "../" . $u['profile_image']
                                    : "../uploads/profile/default.png";
                                ?>

                                <div
                                class="chat-user <?= $u['user_id'] == $receiver_id ? 'active' : ''; ?>"
                                onclick="selectChat(
                                    <?= $u['user_id']; ?>,
                                    '<?= htmlspecialchars($u['username'], ENT_QUOTES); ?>',
                                    '<?= $profile_image; ?>',
                                    this
                                )">

                                    <img src="../<?= str_replace('../','',$profile_image); ?>">
                                    
                                    <div class="chat-user-info">

                                        <div class="chat-user-top">

                                            <h4><?= $u['username']; ?></h4>

                                            <span>Online</span>

                                        </div>

                                        <p>Click to start chat</p>

                                    </div>

                                </div>

                            <?php } ?>

                        </div>

                    </div>

                    <div class="chat-main">
                
                        <div class="chat-header">

                            <div class="chat-header-user">

                                <?php
                                $first_image = !empty($first_user['profile_image'])
                                    ? "../" . $first_user['profile_image']
                                    : "../uploads/profile/default.png";
                                ?>

                                <img
                                id="chatUserImage"
                                src="<?= $first_image; ?>">

                                <div>

                                    <h3 id="chatUserName">
                                        <?= $first_user['username'] ?? 'No User'; ?>
                                    </h3>

                                    <span>Online</span>

                                </div>

                            </div>

                        </div>

                        <div class="chat-messages" id="chatBox"></div>

                        <div class="image-preview-box" id="imagePreviewBox" style="display:none;">
                            <div class="image-preview-list" id="imagePreviewList"></div>
                        </div>

                        <form id="messageForm" class="chat-input-area">

                            <input type="hidden" id="receiver_id" value="<?= $receiver_id; ?>">

                            <button type="button" onclick="document.getElementById('imageInput').click();">
                                <i class="fa-regular fa-image"></i>
                            </button>

                            <input type="file" id="imageInput" accept="image/*" multiple hidden>

                            <button type="button" id="emojiBtn">
                                <i class="fa-regular fa-face-smile"></i>
                            </button>

                            <div class="emoji-picker" id="emojiPicker">
                                <span onclick="addEmoji('😀')">😀</span>
                                <span onclick="addEmoji('😂')">😂</span>
                                <span onclick="addEmoji('😭')">😭</span>
                                <span onclick="addEmoji('😍')">😍</span>
                                <span onclick="addEmoji('😎')">😎</span>
                                <span onclick="addEmoji('👍')">👍</span>
                                <span onclick="addEmoji('🔥')">🔥</span>
                                <span onclick="addEmoji('❤️')">❤️</span>
                                <span onclick="addEmoji('🙏')">🙏</span>
                                <span onclick="addEmoji('✅')">✅</span>
                            </div>

                            <input type="text" id="messageInput" placeholder="Type your message...">

                            <button type="submit" class="send-btn">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>

                        </form>

                    </div>

                </div>

            </main>

        </div>

        <script>
        const chatBox = document.getElementById("chatBox");
        const messageForm = document.getElementById("messageForm");
        const messageInput = document.getElementById("messageInput");
        const receiverInput = document.getElementById("receiver_id");
        const imageInput = document.getElementById("imageInput");
        const imagePreviewBox = document.getElementById("imagePreviewBox");
        const imagePreviewList = document.getElementById("imagePreviewList");
        const emojiBtn = document.getElementById("emojiBtn");
        const emojiPicker = document.getElementById("emojiPicker");

        let selectedImages = [];

        emojiBtn.addEventListener("click", function(){
            emojiPicker.classList.toggle("show");
        });

        function addEmoji(emoji){
            messageInput.value += emoji;
            messageInput.focus();
        }

        function loadMessages(){

            const receiverId = receiverInput.value;

            if(receiverId == 0){
                return;
            }

            fetch("../fetch-messages.php?receiver_id=" + receiverId)
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        }

        imageInput.addEventListener("change", function(){

            const files = Array.from(this.files);

            files.forEach(file => {
                selectedImages.push(file);
            });

            updateImageInput();
            renderImagePreview();
        });

        function renderImagePreview(){

            imagePreviewList.innerHTML = "";

            if(selectedImages.length === 0){
                imagePreviewBox.style.display = "none";
                return;
            }

            imagePreviewBox.style.display = "block";

            selectedImages.forEach((file, index) => {

                const card = document.createElement("div");
                card.className = "image-preview-card";

                card.innerHTML = `
                    <img src="${URL.createObjectURL(file)}">

                    <div>
                        <b>Image selected</b>
                        <p>${file.name}</p>
                    </div>

                    <button type="button" onclick="removeSelectedImage(${index})">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                `;

                imagePreviewList.appendChild(card);
            });
        }

        function removeSelectedImage(index){

            selectedImages.splice(index, 1);

            updateImageInput();
            renderImagePreview();
        }

        function updateImageInput(){

            const dataTransfer = new DataTransfer();

            selectedImages.forEach(file => {
                dataTransfer.items.add(file);
            });

            imageInput.files = dataTransfer.files;
        }

        messageForm.addEventListener("submit", function(e){

            e.preventDefault();

            const message = messageInput.value.trim();
            const receiverId = receiverInput.value;

            if(message === "" && selectedImages.length === 0){
                return;
            }

            const formData = new FormData();
            formData.append("receiver_id", receiverId);
            formData.append("message", message);

            selectedImages.forEach(file => {
                formData.append("images[]", file);
            });

            fetch("../send-message.php", {
                method: "POST",
                body: formData
            })
            .then(() => {
                messageInput.value = "";
                selectedImages = [];
                updateImageInput();
                renderImagePreview();
                loadMessages();
            });
        });

        function selectChat(id, username, image, element){

            receiverInput.value = id;

            document.getElementById("chatUserName").innerText = username;
            document.getElementById("chatUserImage").src = image;

            document.querySelectorAll(".chat-user").forEach(user => {
                user.classList.remove("active");
            });

            element.classList.add("active");

            loadMessages();
        }

        function loadChatUsers(){

            fetch("../fetch-chat-users.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("chatUserList").innerHTML = data;
            });
        }

        setInterval(() => {
            loadMessages();
            loadChatUsers();
        }, 2000);

        loadMessages();
        loadChatUsers();
        </script>

        <div id="imageModal" class="image-modal" onclick="closeImage()">
            <img id="modalImage">
        </div>

        <script>
        function openImage(src){
            document.getElementById("imageModal").style.display = "flex";
            document.getElementById("modalImage").src = src;
        }

        function closeImage(){
            document.getElementById("imageModal").style.display = "none";
        }
        </script>

    </body>
</html>