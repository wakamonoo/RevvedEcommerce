<?php
if(isset($_POST['f_item_name'])){ // Check if form fields are set
    
    // Include the database connection file
    include_once "db.php";
    
    // Retrieve form data
    $item_name = $_POST['f_item_name'];
    $item_price = $_POST['f_item_price'];
    $item_stocks = $_POST['f_item_stocks']; // New field for item stocks
    $item_category = $_POST['f_item_category']; // New field for item category
    
    // File upload handling
    $target_dir = "../img/"; // Specify the directory where uploaded files will be stored
    $target_file = $target_dir . basename($_FILES["f_item_img"]["name"]); // Full path to the uploaded file
    $uploadOk = 1; // Flag to indicate whether the file was uploaded successfully
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["f_item_img"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size (limit to 5MB)
    if ($_FILES["f_item_img"]["size"] > 5000000) {
        echo "Sorry, your file is too large (max size is 5MB).";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    $allowed_extensions = array("jpg", "jpeg", "png");
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if(!in_array($imageFileType, $allowed_extensions)) {
        echo "Sorry, only JPG, JPEG, PNG files are allowed.";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["f_item_img"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["f_item_img"]["name"])). " has been uploaded.";
            
            // File uploaded successfully, now insert data into database
            $sql_insert_item = "INSERT INTO `items` (`item`, `item_img`, `price`, `stocks`, `category`)  
                                VALUES ('$item_name', '$target_file', '$item_price', '$item_stocks', '$item_category')";
            
            if (mysqli_query($conn, $sql_insert_item)) {
                echo "Data inserted successfully.";
                header("location: admin");
                exit(); // Ensure script stops execution after redirection
            } else {
                echo "Error: " . $sql_insert_item . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    header("location: admin??you_cant_be_here");
    exit(); // Ensure script stops execution after redirection
}
?>
