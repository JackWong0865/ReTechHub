<?php
session_start();
include("includes/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['submit_request'])){

    /*validate required fields*/
    $error = "";

    /*check common required fields*/
    if(
        empty($_POST['category']) ||
        empty($_POST['preferred_date']) ||
        empty($_POST['address']) ||
        empty($_POST['description'])
    ){
        $error = "Please complete all required fields.";
    }

    /*validate based on selected service*/
    if($_POST['service_type'] == "sell"){

        if(
            empty($_POST['brand']) ||
            empty($_POST['product_name']) ||
            empty($_POST['condition_type'])
        ){
            $error = "Please complete all required fields.";
        }

    }else{

        if(empty($_POST['repair_type'])){
            $error = "Please select a repair type.";
        }
    }

    /*require at least one image*/
    if(empty($_FILES['images']['name'][0])){
        $error = "Please upload at least one image.";
    }

    /*stop submission if validation fails*/
    if($error != ""){

        $_SESSION['booking_error'] = $error;

        header("Location: booking.php");
        exit();
    }

    $image = "";

    /*get user info*/
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $service_type = $_POST['service_type'];

    /*determine service type*/
    if($service_type == "sell"){
        
        /*sell flow*/
        /*get sell info*/
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $brand = mysqli_real_escape_string($conn, $_POST['brand']);
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $condition_type = mysqli_real_escape_string($conn, $_POST['condition_type']);
        $storage = mysqli_real_escape_string($conn, $_POST['storage']);
        $color = mysqli_real_escape_string($conn, $_POST['color']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $preferred_date = $_POST['preferred_date'];
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        /*save sell requests table*/
        mysqli_query($conn,
            "INSERT INTO sell_requests
            (user_id, device_type, brand, model, condition_type, description, address, preferred_date, status)

            VALUES
            ('$user_id', '$category', '$brand', '$product_name', '$condition_type', '$description', '$address', '$preferred_date', 'Pending')"
        );

        /*get the newly added Request ID*/
        $sell_id = mysqli_insert_id($conn);

        /*upload product picture */
        if(!empty($_FILES['images']['name'][0])){

            $upload_folder = "uploads/sell/";

            if(!is_dir($upload_folder)){
                mkdir($upload_folder, 0777, true);
            }

            foreach($_FILES['images']['name'] as $key => $name){

                if($_FILES['images']['error'][$key] == 0){

                    $filename = time() . "_" . rand(1000,9999) . "_" . basename($name);
                    $target = $upload_folder . $filename;

                    if(move_uploaded_file($_FILES['images']['tmp_name'][$key], $target)){

                        /*save to booking images table*/
                        mysqli_query($conn,
                            "INSERT INTO booking_images
                            (booking_type, booking_id, image_path)
                            VALUES
                            ('sell', '$sell_id', '$target')"
                        );
                    }
                }
            }
        }

        /*notify admin*/
        $admin_id = 1;

            mysqli_query($conn,
                "INSERT INTO notifications
                (user_id, title, message, type)
                VALUES
                ('$admin_id', 'New Sell Request', 'User #$user_id submitted a new sell request.', 'booking')"
            );

        /*show success message*/
        $_SESSION['booking_success'] = "Sell request submitted successfully!";
        header("Location: booking.php");
        exit();

    }else{

        /*repair flow*/
        /*get repair info*/
        $device_type = mysqli_real_escape_string($conn, $_POST['category']);
        $repair_type = mysqli_real_escape_string($conn, $_POST['repair_type']);
        $issue = mysqli_real_escape_string($conn, $_POST['description']);
        $preferred_date = $_POST['preferred_date'];
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        /*save to repair booking table*/
        mysqli_query($conn,
            "INSERT INTO repair_bookings
            (user_id, device_type, repair_type, issue_description, address, preferred_date, status)

            VALUES
            ('$user_id', '$device_type', '$repair_type', '$issue', '$address', '$preferred_date', 'Pending')"
        );

        /*get booking id*/
        $booking_id = mysqli_insert_id($conn);

        /*upload repair images*/
        if(!empty($_FILES['images']['name'][0])){

            $upload_folder = "uploads/repair/";

            if(!is_dir($upload_folder)){
                mkdir($upload_folder, 0777, true);
            }

            foreach($_FILES['images']['name'] as $key => $name){

                if($_FILES['images']['error'][$key] == 0){

                    $filename = time() . "_" . rand(1000,9999) . "_" . basename($name);
                    $target = $upload_folder . $filename;

                    if(move_uploaded_file($_FILES['images']['tmp_name'][$key], $target)){

                        /*save booking images table*/
                        mysqli_query($conn,
                            "INSERT INTO booking_images
                            (booking_type, booking_id, image_path)
                            VALUES
                            ('repair', '$booking_id', '$target')"
                        );
                    }
                }
            }
        }

        /*notify admin*/
        $admin_id = 1;

            mysqli_query($conn,
                "INSERT INTO notifications
                (user_id, title, message, type)
                VALUES
                ('$admin_id', 'New Repair Booking', 'User #$user_id $username submitted a new repair booking.', 'booking')"
            );

        /*shows successful message*/
        $_SESSION['booking_success'] = "Repair request submitted successfully!";
        header("Location: booking.php");
        exit();
    }
}

include("includes/header.php");
?>

<link rel="stylesheet" href="assets/css/service-request.css">

<div class="service-request-page">

    <form method="POST" enctype="multipart/form-data" class="request-grid">

        <!--service card-->
        <div class="left-area">

            <div class="card-box">

                <h3>1. Choose a Service</h3>

                <div class="service-choice">

                    <label class="choice active">
                        <input type="radio" name="service_type" value="sell" checked>

                        <div class="choice-icon">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>

                        <div>
                            <h4>Sell My Device</h4>
                            <p>List your used device and reach thousands of buyers.</p>
                        </div>
                    </label>

                    <label class="choice">
                        <input type="radio" name="service_type" value="repair">

                        <div class="choice-icon">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>

                        <div>
                            <h4>Repair My Device</h4>
                            <p>Book a professional repair service for your device.</p>
                        </div>
                    </label>

                </div>

            </div>

            <!--product detail card-->
            <div class="card-box">

                <div class="section-title">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <div>
                        <h3>Product Details</h3>
                        <p>Provide basic information about your device.</p>
                    </div>
                </div>

                <div class="form-grid">

                    <div>
                        <label>Category *</label>
                        <select name="category" required>
                            <option value="">Select category</option>
                            <option>Smartphone</option>
                            <option>Laptop</option>
                            <option>Tablet</option>
                            <option>Gaming</option>
                            <option>Accessories</option>
                            <option>Camera</option>
                        </select>
                    </div>

                    <div class="repair-fields">

                        <div>
                            <label>Repair Type *</label>

                            <select name="repair_type">

                                <option value="">Select repair type</option>

                                <option>Screen Repair</option>
                                <option>Battery Replacement</option>
                                <option>Water Damage</option>
                                <option>Software Issue</option>
                                <option>Hardware Repair</option>
                                <option>Other</option>

                            </select>
                        </div>

                    </div>

                    <div>
                        <label>Brand *</label>
                        <input type="text" name="brand" placeholder="Ex: iPhone">
                    </div>

                    <div>
                        <label>Product Name *</label>
                        <input type="text" name="product_name" placeholder="Ex: iPhone 13 Pro 128GB">
                    </div>

                    <div id="sellCondition">

                        <div class="condition-title">

                            <label>Condition *</label>

                            <button type="button" class="how-btn" onclick="openConditionModal()">
                                How it works?
                            </button>

                        </div>

                        <select name="condition_type">
                            <option value="">Select condition</option>
                            <option>Like New</option>
                            <option>Good</option>
                            <option>Fair</option>
                            <option>Used</option>
                        </select>

                    </div>
                    
                    <div>
                        <label>Storage / Variant</label>
                        <input type="text" name="storage" placeholder="Ex: 128GB">
                    </div>

                    <div>
                        <label>Color</label>
                        <input type="text" name="color" placeholder="Ex: White">
                    </div>

                    <div>
                        <label>Preferred Date *</label>
                        <input type="date" name="preferred_date">
                    </div>

                    <div>
                        <label>Address *</label>
                        <input type="text" name="address">
                    </div>

                </div>

            </div>

            <!--description card-->
            <div class="card-box">

                <div class="section-title">
                    <i class="fa-solid fa-align-left"></i>
                    <div>
                        <h3>Description</h3>
                        <p>Describe your device's condition, features and any defects.</p>
                    </div>
                </div>

                <textarea name="description" class="description-box" placeholder="Write a detailed description..." required></textarea>
            </div>

        </div>

        <!--upload picture card-->
        <div class="right-area">

            <div class="card-box">

                <div class="section-title">
                    <i class="fa-solid fa-image"></i>
                    <div>
                        <h3>Upload Images</h3>
                        <p>Add clear photos of your device.</p>
                    </div>
                </div>

                <label class="upload-box" for="deviceImages">
                    <input type="file" name="images[]" multiple id="deviceImages" accept="image/*" hidden>

                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <h3>Drag & drop your images here</h3>
                    <p>or click to browse</p>
                    <small>JPG, PNG up to 10MB each</small>
                </label>

                <div class="photo-grid" id="previewBox">
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                    <div><i class="fa-regular fa-image"></i><span>Add Photo</span></div>
                </div>

            </div>

            <!--tips card-->
            <div class="card-box review-box">

                <div class="section-title">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <div>
                        <h3>Review & Submit</h3>
                        <p>Review your request details before submitting.</p>
                    </div>
                </div>

                <div class="review-panel">
                    <h4>Before you submit</h4>
                    <p><i class="fa-solid fa-circle-check"></i>Ensure all information is accurate</p>
                    <p><i class="fa-solid fa-circle-check"></i>Add clear and real images</p>
                    <p><i class="fa-solid fa-circle-check"></i>Provide complete device details</p>
                </div>

                <button type="submit" name="submit_request" class="publish-btn" id="submitRequestBtn">
                    <span id="submitRequestText">Submit Sell Request</span>
                    <i class="fa-regular fa-paper-plane"></i>
                </button>

                <div class="approval-note">
                    <i class="fa-solid fa-lock"></i>
                    Your request will be reviewed after submission
                </div>

            </div>

        </div>

    </form>

    <!--how it work tips-->
    <div class="condition-modal" id="conditionModal">

        <div class="condition-modal-box">

            <button class="condition-close" type="button" onclick="closeConditionModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <h2>Condition Guide</h2>

            <div class="condition-item">

                <div class="condition-icon icon-like">
                    <i class="fa-solid fa-star"></i>
                </div>

               <div>
                    <h4>Like New</h4>
                    <p>
                        Device is in excellent condition with almost no scratches or signs of use.
                        All functions work perfectly.
                    </p>
                </div>
            </div>

            <div class="condition-item">

                <div class="condition-icon icon-good">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>

                <div>
                    <h4>Good</h4>
                    <p>
                        Minor cosmetic scratches or marks,
                        but the device is fully functional.
                    </p>
                </div>

            </div>

            <div class="condition-item">

                <div class="condition-icon icon-fair">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </div>

                <div>
                    <h4>Fair</h4>
                    <p>
                        Noticeable scratches or wear,
                        but still works properly.
                    </p>
                </div>
            </div>

            <div class="condition-item">

                <div class="condition-icon icon-used">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>

                <div>
                    <h4>Used</h4>
                    <p>
                        Heavy signs of use.
                        Device is still usable but may have cosmetic defects.
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

<!--service type-->
<script>
const serviceRadios =document.querySelectorAll("input[name='service_type']");

const choices =document.querySelectorAll(".choice");

const repairFields =document.querySelector(".repair-fields");

const submitRequestText =document.getElementById("submitRequestText");

function updateServiceUI(){

    const selected =
        document.querySelector(
            "input[name='service_type']:checked"
        ).value;

    choices.forEach(choice => {

        choice.classList.remove("active");

        const radio =
            choice.querySelector(
                "input[name='service_type']"
            );

        if(radio.checked){
            choice.classList.add("active");
        }

    });

    if(selected === "repair"){

        repairFields.style.display = "block";

        document.getElementById(
            "sellCondition"
        ).style.display = "none";

        submitRequestText.textContent =
            "Submit Repair Request";

    }else{

        repairFields.style.display = "none";

        document.getElementById(
            "sellCondition"
        ).style.display = "block";

        submitRequestText.textContent =
            "Submit Sell Request";
    }
}

serviceRadios.forEach(radio => {

    radio.addEventListener(
        "change",
        updateServiceUI
    );

});

window.addEventListener(
    "DOMContentLoaded",
    updateServiceUI
);
</script>

<!--picture function-->
<script>
const imageInput = document.getElementById("deviceImages");
const previewBox = document.getElementById("previewBox");

let uploadedFiles = [];

imageInput.addEventListener("change", function(){

    const newFiles = Array.from(this.files);

    newFiles.forEach(file => {
        if(uploadedFiles.length < 6){
            uploadedFiles.push(file);
        }
    });

    updateInputFiles();
    renderPreview();
});

function renderPreview(){

    previewBox.innerHTML = "";

    uploadedFiles.forEach((file, index) => {

        const reader = new FileReader();

        reader.onload = function(e){

            const box = document.createElement("div");
            box.classList.add("preview-photo-box");

            box.innerHTML = `
                <button type="button" class="remove-photo-btn" onclick="removePhoto(${index})">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <img src="${e.target.result}" class="preview-photo">
            `;

            previewBox.appendChild(box);
        };

        reader.readAsDataURL(file);
    });

    for(let i = uploadedFiles.length; i < 6; i++){

        const empty = document.createElement("div");

        empty.innerHTML = `
            <i class="fa-regular fa-image"></i>
            <span>Add Photo</span>
        `;

        previewBox.appendChild(empty);
    }
}

function removePhoto(index){

    uploadedFiles.splice(index, 1);

    updateInputFiles();
    renderPreview();
}

function updateInputFiles(){

    const dataTransfer = new DataTransfer();

    uploadedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });

    imageInput.files = dataTransfer.files;
}

/*how it work*/
function openConditionModal(){
    document.getElementById("conditionModal").style.display = "flex";
}

function closeConditionModal(){
    document.getElementById("conditionModal").style.display = "none";
}

window.onclick = function(e){

    if(e.target == document.getElementById("conditionModal")){
        closeConditionModal();
    }

}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(isset($_SESSION['booking_success'])){ ?>

<script>
Swal.fire({
    icon: "success",
    title: "Request Submitted",
    text: <?= json_encode($_SESSION['booking_success']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>

<?php
unset($_SESSION['booking_success']);
}
?>

<!--validation error window-->
<?php if(isset($_SESSION['booking_error'])){ ?>

<script>
Swal.fire({
    icon: "error",
    title: "Unable to Submit Request",
    text: <?= json_encode($_SESSION['booking_error']); ?>,
    confirmButtonText: "OK",
    confirmButtonColor: "#e53935"
});
</script>

<?php
unset($_SESSION['booking_error']);
}
?>