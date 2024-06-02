<?php
include_once "db.php";

$fullname = $_POST['r_fullname'];
$uname = $_POST['r_username'];
$passwd = $_POST['r_password'];
$conf_passwd = $_POST['r_conf_password'];
$address = $_POST['r_address'];

// Function to check if passwords match
function chk_pass($p1, $p2) {
    return ($p1 == $p2) ? 1 : 0;
}

// Check if passwords match
if(!chk_pass($passwd, $conf_passwd)){
    header("Location: registration.php?error=password_mismatch");
    exit; // Stop script execution
}

// Check if the username already exists
$sql_chk_user = "SELECT user_id FROM users WHERE uname = '$uname'";
$sql_result = mysqli_query($conn, $sql_chk_user);
$count_result = mysqli_num_rows($sql_result);

if($count_result > 0){
    // User already exists
    header("Location: registration.php?error=user_already_exist");
    exit; // Stop script execution
}

// Handle file upload
$target_dir = "uploads/"; // Use the img directory
$target_file = $target_dir . basename($_FILES["r_user_img"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["r_user_img"]["tmp_name"]);
if($check !== false) {
    $uploadOk = 1;
} else {
    header("Location: registration.php?error=not_an_image");
    exit; // Stop script execution
}

// Check file size
if ($_FILES["r_user_img"]["size"] > 500000) { // 500KB
    header("Location: registration.php?error=file_too_large");
    exit; // Stop script execution
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    header("Location: registration.php?error=wrong_file_format");
    exit; // Stop script execution
}

// Try to upload file
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["r_user_img"]["tmp_name"], $target_file)) {
        // File is uploaded successfully, proceed with user registration
        $user_img = basename($_FILES["r_user_img"]["name"]); // Store only the file name
        $sql_new_user = "INSERT INTO users (uname, password, fname, address, user_cat, user_img)
                         VALUES ('$uname', '$passwd', '$fullname', '$address', 'U', '$user_img')";
        $execute_query = mysqli_query($conn, $sql_new_user);
        
        if(!$execute_query){
            header("Location: registration.php?error=Insert_Failed");
            exit; // Stop script execution
        } else {
            header("Location: login.php?msg=successfully_registered");
            exit; // Stop script execution
        }
    } else {
        header("Location: registration.php?error=upload_failed");
        exit; // Stop script execution
    }
} else {
    header("Location: registration.php?error=upload_failed");
    exit; // Stop script execution
}
?>
