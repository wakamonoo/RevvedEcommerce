<?php
// Include the database connection file
include_once "db.php";

// Check if the form fields are set
if(isset($_POST['u_item_name'], $_POST['u_item_price'], $_POST['u_item_stock'], $_POST['u_item_id'], $_POST['u_item_category'])){
    
    // Get the form data
    $item_id = $_POST['u_item_id'];
    $item_name = $_POST['u_item_name'];
    $item_price = $_POST['u_item_price'];
    $item_stock = $_POST['u_item_stock']; // Retrieve the updated stock quantity
    $item_category = $_POST['u_item_category']; // Retrieve the updated category

    // Check if a file was uploaded
    if(isset($_FILES['u_item_img']) && $_FILES['u_item_img']['error'] === UPLOAD_ERR_OK){
        // Specify the target directory for uploads
        $target_dir = "../img/";

        // Specify the target file
        $target_file = $target_dir . basename($_FILES['u_item_img']['name']);

        // File upload handling
        $check = getimagesize($_FILES["u_item_img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
        
        // Check file size (limit to 5MB)
        if ($_FILES["u_item_img"]["size"] > 5000000) {
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        $allowed_extensions = array("jpg", "jpeg", "png");
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if(!in_array($imageFileType, $allowed_extensions)) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            // Handle the error condition here
        } else {
            if(move_uploaded_file($_FILES['u_item_img']['tmp_name'], $target_file)){
                // Update the item in the database with the new image path, stock quantity, and category
                $sql_update_item = "UPDATE `items`
                                    SET `item` = ?,
                                        `item_img` = ?,
                                        `price` = ?,
                                        `stocks` = ?,
                                        `category` = ?
                                    WHERE `item_id` = ?";

                // Prepare and execute the SQL statement
                $stmt = mysqli_prepare($conn, $sql_update_item);
                mysqli_stmt_bind_param($stmt, "ssdiss", $item_name, $target_file, $item_price, $item_stock, $item_category, $item_id);
                
                if(mysqli_stmt_execute($stmt)) {
                    // Redirect to admin.php with update status
                    header("location: admin.php?update_status=1");
                    exit();
                } else {
                    // Display error message
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                // If file upload failed, redirect to admin.php with an error message
                echo "File upload failed. Error code: " . $_FILES['u_item_img']['error'] . "<br>";
                header("location: admin.php?error=file_upload_failed");
                exit();
            }
        }
    } else {
        // If no file was uploaded, update the item in the database without changing the image path but updating the stock quantity and category
        $sql_update_item = "UPDATE `items`
                            SET `item` = ?,
                                `price` = ?,
                                `stocks` = ?,
                                `category` = ?
                            WHERE `item_id` = ?";

        // Prepare and execute the SQL statement
        $stmt = mysqli_prepare($conn, $sql_update_item);
        mysqli_stmt_bind_param($stmt, "sidsi", $item_name, $item_price, $item_stock, $item_category, $item_id);
        
        if(mysqli_stmt_execute($stmt)) {
            // Redirect to admin.php with update status
            header("location: admin/index.php?update_status=1");
            exit();
        } else {
            // Display error message
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
} else {
    // If form fields are not set, redirect to admin.php with an error message
    header("location: admin/index.php?error=missing_fields");
    exit();
}
?>
