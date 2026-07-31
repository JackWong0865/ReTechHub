<script>
function loadLiveBadges(){

    const msgBadge = document.getElementById("messageBadge");
    const notiBadge = document.getElementById("notificationBadge");

    let prefix = "";

    if(window.location.pathname.includes("/admin/") ||
       window.location.pathname.includes("/technician/")){
        prefix = "../";
    }

    if(msgBadge){
        fetch(prefix + "fetch-message-count.php")
        .then(res => res.text())
        .then(count => {
            count = parseInt(count);

            if(count > 0){
                msgBadge.style.display = "flex";
                msgBadge.innerText = count;
            }else{
                msgBadge.style.display = "none";
            }
        });
    }

    if(notiBadge){
        fetch(prefix + "fetch-notification-count.php")
        .then(res => res.text())
        .then(count => {
            count = parseInt(count);

            if(count > 0){
                notiBadge.style.display = "flex";
                notiBadge.innerText = count;
            }else{
                notiBadge.style.display = "none";
            }
        });
    }
}

setInterval(loadLiveBadges, 2000);
loadLiveBadges();
</script>