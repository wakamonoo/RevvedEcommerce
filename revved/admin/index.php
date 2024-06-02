<?php
include "../db.php";
// Ensure session is started
session_start();

// Check if the user is not an admin, redirect to index.php if not
if(!isset($_SESSION['user_cat']) || $_SESSION['user_cat'] !== 'A'){
    header("location: ../index.php");
    exit(); // Ensure script stops execution after redirection
}

if(isset($_GET['logout'])){
    session_destroy();
    header("location: ../index.php");
    die();
}

if(isset($_GET['delete_from_cart'])){
    $order_id = $_GET['delete_from_cart'];
    $sql_delete_from_cart = "DELETE FROM `order` WHERE `order_id` = '$order_id'";
    $sql_execute = mysqli_query($conn, $sql_delete_from_cart);
    if($sql_execute){
        header("location: index.php?msg=cart_item_removed");
    }
}

// Query to retrieve items from the database
$sql_get_items = "SELECT * FROM `items` ORDER BY item_id DESC";
$get_result = mysqli_query($conn, $sql_get_items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #343a40; /* Dark gray background */
            color: #ffffff; /* White text */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .toolbar {
            background-color: #212529;
            padding: 10px;
            border-bottom: 2px solid #ffffff; /* Adding a white bottom outline */
        }
        .toolbar a {
            color: #ffffff;
            margin-right: 20px;
            text-decoration: none;
            font-size: 18px;
        }
        .toolbar a:hover {
            color: #dc3545; /* Red hover */
        }
        .card {
            background-color: #212529; /* Dark background for cards */
            color: #ffffff; /* White text for cards */
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .card-header {
            background-color: #343a40; /* Dark gray background for card headers */
            color: #ffffff; /* White text for card headers */
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 20px;
        }
        .btn {
            border-radius: 5px;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .btn-success {
            background-color: #28a745; /* Green button */
            border: 1px solid #28a745;
        }
        .btn-success:hover {
            background-color: #218838; /* Dark green hover */
            border-color: #1e7e34;
        }
        .btn-danger {
            background-color: #dc3545; /* Red button */
            border: 1px solid #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333; /* Dark red hover */
            border-color: #bd2130;
        }
        .table {
            background-color: #212529; /* Dark background for tables */
            color: #ffffff; /* White text for tables */
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ffffff;
        }
        .table th {
            background-color: #343a40; /* Dark gray background for table headers */
        }
        .table tbody tr:nth-child(even) {
            background-color: #343a40; /* Dark gray background for even rows */
        }
        .table tbody tr:hover {
            background-color: #495057; /* Darker gray hover */
        }
        .form-control {
            background-color: #343a40; /* Dark gray background for form controls */
            color: #ffffff; /* White text for form controls */
            border: 1px solid #ffffff; /* White border for form controls */
            border-radius: 5px;
            padding: 10px;
            width: 100%;
        }
        .form-control:focus {
            background-color: #495057; /* Darker gray background when focused */
            color: #ffffff; /* White text when focused */
            border-color: #ffffff; /* White border when focused */
        }
        .form-control::placeholder {
            color: #ced4da; /* Light gray placeholder text */
        }
        .display-3 {
            text-transform: uppercase;
            font-size: 24px;
            margin: 10px 0;
        }
        .logo {
            max-width: 100px;
            margin-right: 20px;
        }
        .btn-update {
            background-color: #007bff; /* Blue button */
            border: 1px solid #007bff;
        }
        .btn-update:hover {
            background-color: #0056b3; /* Darker Blue hover */
            border-color: #0056b3;
        }
        .col-md-8{
            margin-top: 20px;
            width: 5000px !important;
        }
    </style>
</head>
<body>
<div class="toolbar">
    <a href="index.php">Home</a>
    <a href="?logout">Logout</a>
    <a href="newitem.php">Add New Item</a>
    <a href="manage_order.php">Manage Order</a>
    <a href="dashboard.php">Dashboard</a>
</div>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <img src="../img/logo.png" alt="revved logo" class="logo">
                    <h3 class="display-3">Welcome <?php echo $_SESSION['username']; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <?php
                            if(isset($_GET['deactivate_item'])){
                                $item_id = $_GET['deactivate_item'];
                                $sql_get_item_status = "SELECT item_status FROM items WHERE item_id='$item_id'";
                                $result = mysqli_query($conn, $sql_get_item_status);
                                $data_row = mysqli_fetch_assoc($result);
                                $item_status = $data_row['item_status'];
                                // Check the current status of the item and toggle it
                                if($item_status == 'A'){
                                    $new_status = 'D';
                                } else {
                                    $new_status = 'A';
                                }

                                $sql_update_item_status = "UPDATE `items` SET `item_status`='$new_status' WHERE `item_id`='$item_id'";
                                mysqli_query($conn, $sql_update_item_status);
                            }
                            if(isset($_GET['update_item'])){
                                $item_id = $_GET['update_item'];
                                
                                $sql_get_item_info = "SELECT * FROM `items` WHERE item_id = '$item_id'";
                                $result = mysqli_query($conn, $sql_get_item_info);
                                $data_row = mysqli_fetch_assoc($result);
                                ?>    
                                <!-- Update Item Info Form -->
                                    <h3 class="display-3">Update Item Info</h3>
                                    <form action="../process_update_item.php" method="POST" enctype="multipart/form-data">
                                        <label for="u_item_id">Item Id</label>
                                        <input value="<?php echo $data_row['item_id'];?>" type="text" name="u_item_id" readonly class="form-control mb-3">
                                        
                                        <label for="u_item_name">Item Name</label>
                                        <input value="<?php echo $data_row['item'];?>" type="text" name="u_item_name" class="form-control mb-3">

                                        <label for="u_item_img">Item Image</label>
                                        <input type="file" name="u_item_img" class="form-control mb-3">
                                        
                                        <!-- Display current item image -->
                                        <img src="<?php echo $data_row['item_img']; ?>" alt="Current Item Image" style="max-width: 200px;"><br><br>

                                        <label for="u_item_price">Item Price</label>
                                        <input value="<?php echo $data_row['price'];?>" type="text" name="u_item_price" class="form-control mb-3">

                                        <!-- Input field for stock quantity -->
                                        <label for="u_item_stock">Item Stock</label>
                                        <input value="<?php echo $data_row['stocks'];?>" type="text" name="u_item_stock" class="form-control mb-3">

                                        <!-- Dropdown for selecting category -->
                                        <label for="u_item_category">Item Category</label>
                                        <select name="u_item_category" class="form-control mb-3">
                                            <option value="Frame and Body Parts">Frame and Body Parts</option>
                                            <option value="Performance Parts">Performance Parts</option>
                                            <option value="Accessories and Add-ons">Accessories and Add-ons</option>
                                            <option value="Lights and Electrical">Lights and Electrical</option>
                                            <option value="Engine and Internal Parts">Engine and Internal Parts</option>
                                        </select>

                                        <input type="submit" class="btn btn-primary" value="Update">
                                    </form>
                                <?php
                            }
                            ?>
                            <!-- Display Items Table -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                            <th>Stocks</th> <!-- Add Stocks column -->
                                            <th>Update</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($get_result)) { ?>
                                            <tr>
                                                <td><img src="<?php echo $row['item_img']; ?>" alt="Item Image" style="max-width: 100px;"></td>
                                                <td><?php echo htmlspecialchars($row['item']); ?></td>
                                                <td><?php echo "Php " . number_format($row['price'], 2); ?></td>
                                                <td><?php echo $row['stocks']; ?></td> <!-- Display the stocks -->
                                                <td><a href="index.php?update_item=<?php echo $row['item_id']; ?>" class="btn btn-update">Update</a></td>
                                                <td>
                                                    <?php if ($row['item_status'] == 'A'): ?>
                                                        <a href="index.php?deactivate_item=<?php echo $row['item_id']; ?>" class="btn btn-danger">Deactivate</a>
                                                    <?php else: ?>
                                                        <a href="index.php?deactivate_item=<?php echo $row['item_id']; ?>" class="btn btn-success">Activate</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="../js/bootstrap.js"></script>
</body>
</html>

