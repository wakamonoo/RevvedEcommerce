<?php
session_start();

include_once "../db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not_logged_in");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql_fetch_user = "SELECT user_id, uname, fname, address, user_cat, user_img, password FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql_fetch_user);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php?error=user_not_found");
    exit;
}

// Validate the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $uname = $_POST['uname'];
    $fname = $_POST['fname'];
    $address = $_POST['address'];
    $password = $_POST['password']; // New password field
    $user_img = $user['user_img']; // Default to the existing image
    
    // Handle file upload for profile picture
    if ($_FILES["user_img"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/"; // Specify the target directory
        $target_file = $target_dir . basename($_FILES["user_img"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check file size and format
        if ($_FILES["user_img"]["size"] > 500000 || !in_array($imageFileType, array("jpg", "png", "jpeg", "gif"))) {
            header("Location: information.php?error=invalid_file");
            exit;
        }
        
        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES["user_img"]["tmp_name"], $target_file)) {
            header("Location: information.php?error=file_upload_failed");
            exit;
        }
        
        $user_img = basename($_FILES["user_img"]["name"]);
    }
    
    // Update user information in the database
    $update_query = "UPDATE users SET uname = '$uname', fname = '$fname', address = '$address', user_img = '$user_img'";
    
    // Update password if a new password is provided
    if (!empty($password)) {
        $update_query .= ", password = '$password'";
    }
    
    $update_query .= " WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: information.php?msg=update_success");
        exit;
    } else {
        header("Location: information.php?error=update_failed");
        exit;
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: update.php");
    exit;
}
?>
