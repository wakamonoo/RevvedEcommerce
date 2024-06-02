<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Item</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #343a40; /* Dark gray background */
            color: #ffffff; /* White text */
        }
        .form-control {
            background-color: #343a40; /* Dark gray background for form controls */
            color: #ffffff; /* White text for form controls */
            border: 1px solid #ffffff; /* White border for form controls */
        }
        .form-control:focus {
            background-color: #495057; /* Darker gray background when focused */
            color: #ffffff; /* White text when focused */
            border: 1px solid #ffffff; /* White border when focused */
        }
        .alert {
            background-color: #343a40; /* Dark gray background for alerts */
            color: #ffffff; /* White text for alerts */
            border: 1px solid #ffffff; /* White border for alerts */
        }
        .alert-warning {
            background-color: #ffc107; /* Yellow background for warning alerts */
            color: #343a40; /* Dark text for warning alerts */
            border-color: #ffc107; /* Yellow border for warning alerts */
        }
        .container-fluid {
            padding-top: 50px; /* Adjust padding */
        }
        h3.display-6 {
            color: #dc3545; /* Red title */
            text-align: center; /* Center align the title */
            margin-bottom: 30px; /* Adjust margin */
            text-transform: uppercase; /* Convert text to uppercase */
            font-family: 'Montserrat', sans-serif; /* Applies a different font to the username */
        }

        form {
            background-color: #212529; /* Dark background for form */
            padding: 20px; /* Add padding */
            border-radius: 10px; /* Rounded corners */
        }
        label {
            color: #ffffff; /* White text for labels */
            margin-bottom: 10px; /* Adjust margin */
        }
        input[type="text"],
        input[type="file"] {
            background-color: #495057; /* Darker gray background for inputs */
            color: #ffffff; /* White text for inputs */
            border: 1px solid #ffffff; /* White border for inputs */
            margin-bottom: 15px; /* Adjust margin */
        }
        input[type="submit"] {
            background-color: #007bff; /* Blue submit button */
            color: #ffffff; /* White text for button */
            border: none; /* No border */
            padding: 10px 20px; /* Add padding */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Cursor style */
        }
        input[type="submit"]:hover {
            background-color: #0056b3; /* Dark blue hover */
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <h3 class="display-6">Add New Item</h3>
            <?php 
            if(isset($_GET['insert_status'])){
                echo "<div class='alert alert-warning'>";
                if($_GET['insert_status'] == '1') {
                    echo "Item Added Successfully.";
                } else{
                    echo "There was an error.";
                }
                echo "</div>";
            }
            ?>
            <form action="../process_new_item.php" method="post" enctype="multipart/form-data">
                <label for="f_item_name">Item Name</label>
                <input type="text" name="f_item_name" id="f_item_name" class="form-control mb-3">
                
                <label for="f_item_img">Item Image</label>
                <input type="file" name="f_item_img" id="f_item_img" class="form-control mb-3">
                
                <label for="f_item_price">Item Price</label>
                <input type="text" name="f_item_price" id="f_item_price" class="form-control mb-3">
                
                <!-- Add input field for item stocks -->
                <label for="f_item_stocks">Item Stocks</label>
                <input type="text" name="f_item_stocks" id="f_item_stocks" class="form-control mb-3">
                
                <!-- Dropdown for selecting category -->
                <label for="f_item_category">Item Category</label>
                <select name="f_item_category" id="f_item_category" class="form-control mb-3">
                    <option value="Frame and Body Parts">Frame and Body Parts</option>
                    <option value="Performance Parts">Performance Parts</option>
                    <option value="Accessories and Add-ons">Accessories and Add-ons</option>
                    <option value="Lights and Electrical">Lights and Electrical</option>
                    <option value="Engine and Internal Parts">Engine and Internal Parts</option>
                </select>
                
                <input type="submit" value="Submit" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<script src="../js/bootstrap.js"></script>
</body>
</html>