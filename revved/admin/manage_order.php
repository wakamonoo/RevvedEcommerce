<?php
include "../db.php";
session_start();

if(!isset($_SESSION['user_cat']) || $_SESSION['user_cat'] !== 'A'){
    header("location: ../index.php");
    exit();
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

if(isset($_POST['confirm_order'])){
    $order_ref_number = $_POST['order_ref_number'];
    $sql_confirm_order = "UPDATE `order` SET `status` = 3 WHERE `order_ref_number` = '$order_ref_number'";
    $sql_execute = mysqli_query($conn, $sql_confirm_order);
    if($sql_execute){
        header("location: manage_order.php?msg=order_confirmed");
    }
}

if(isset($_POST['cancel_order'])){
    $order_ref_number = $_POST['order_ref_number'];
    $sql_cancel_order = "UPDATE `order` SET `status` = 0 WHERE `order_ref_number` = '$order_ref_number'";
    $sql_execute = mysqli_query($conn, $sql_cancel_order);
    if($sql_execute){
        header("location: manage_order.php?msg=order_cancelled");
    }
}

if(isset($_POST['ship_order'])){
    $order_ref_number = $_POST['order_ref_number'];
    $sql_ship_order = "UPDATE `order` SET `status` = 4 WHERE `order_ref_number` = '$order_ref_number'";
    $sql_execute = mysqli_query($conn, $sql_ship_order);
    if($sql_execute){
        header("location: manage_order.php?msg=order_shipped");
    }
}

if(isset($_POST['finish_order'])){
    $order_ref_number = $_POST['order_ref_number'];
    $sql_shipping = "UPDATE `order` SET `status` = 5 WHERE `order_ref_number` = '$order_ref_number'";
    $sql_execute = mysqli_query($conn, $sql_shipping);
    if($sql_execute){
        header("location: manage_order.php?msg=order_completed");
    }
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .toolbar {
            background-color: #212529;
            padding: 10px;
            border-bottom: 2px solid #ffffff;
        }
        .toolbar a {
            color: #ffffff;
            margin-right: 20px;
            text-decoration: none;
            font-size: 18px;
        }
        .toolbar a:hover {
            color: #dc3545;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .table {
            color: #ffffff;
        }
        .card {
            background-color: #212529;
            color: #ffffff;
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 20px;
        }
        .form-control {
            background-color: #343a40;
            color: #ffffff;
            border: 1px solid #ffffff;
        }
        .form-control:focus {
            background-color: #495057;
            color: #ffffff;
            border: 1px solid #ffffff;
        }
        .alert {
            background-color: #343a40;
            color: #ffffff;
            border: 1px solid #ffffff;
        }
        .alert-warning {
            background-color: #ffc107;
            color: #343a40;
        }
        .table th,
        .table td {
            padding: 20px;
            font-size: 16px;
        }
        .display-3{
            text-transform: uppercase;
            font-size: 24px;
            font-family: sans-serif;  
            margin-left: 10px;    
        }
        .display-4{
            text-transform: uppercase;
            font-size: 30px;
            font-family: sans-serif;  
            margin-left: 10px;   
            color: #dc3545;
        }
        .navbar {
            background-color: #343a40;
            padding: 10px;
            border-radius: 5px;
        }
        .navbar a {
            color: #ffffff;
            margin-right: 20px;
            text-decoration: none;
            font-size: 18px;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #495057;
        }
        .navbar a.active {
            background-color: #007bff;
        }
        .col-12{
            margin-top: 20px;
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <img src="../img/logo.png" alt="revved logo" class="logo">
                        <h3 class="display-3">
                            Welcome <?php echo $_SESSION['username']; ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="navbar">
                            <a href="?manageorder&status=2" class="<?php echo $status == 2 ? 'active' : ''; ?>">Pending</a>
                            <a href="?manageorder&status=3" class="<?php echo $status == 3 ? 'active' : ''; ?>">To Ship</a>
                            <a href="?manageorder&status=4" class="<?php echo $status == 4 ? 'active' : ''; ?>">Shipping</a>
                            <a href="?manageorder&status=5" class="<?php echo $status == 5 ? 'active' : ''; ?>">Completed</a>
                            <a href="?manageorder&status=0" class="<?php echo $status == 0 ? 'active' : ''; ?>">Cancelled</a>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Order Details:</h4>
                                <?php
                                // Fetch orders based on status with item details
                                $sql_orders = "SELECT `order`.*, `items`.`item`, `items`.`item_img`, `shippers`.`shipping_company`, `users`.`fname`, `users`.`address` 
                                    FROM `order` 
                                    JOIN `items` ON `order`.`item_id` = `items`.`item_id`
                                    JOIN `shippers` ON `order`.`shipper_id` = `shippers`.`shipper_id`
                                    JOIN `users` ON `order`.`user_id` = `users`.`user_id`
                                    WHERE `order`.`status` = '$status'
                                    ORDER BY `order`.`order_ref_number`";

                                $result_orders = mysqli_query($conn, $sql_orders);

                                // Group orders by order_ref_number
                                $orders = [];
                                while ($row = mysqli_fetch_assoc($result_orders)) {
                                    $orders[$row['order_ref_number']][] = $row;
                                }

                                // Display orders grouped by order_ref_number
                                if (!empty($orders)) {
                                    foreach ($orders as $order_ref_number => $order_group) {
                                        echo "<div class='card mb-3'>";
                                        echo "<div class='card-body'>";
                                        echo "<h5 class='card-title'>Order Reference Number: " . $order_ref_number . "</h5>";
                                        foreach ($order_group as $order) {
                                            echo "<div class='row'>";
                                            echo "<div class='col-md-3'><img src='" . $order['item_img'] . "' alt='Item Image' class='card-img-top' style='max-width: 200px;'></div>";
                                            echo "<div class='col-md-9'>";
                                            echo "<p class='card-text'>Item: " . $order['item'] . "</p>";
                                            echo "<p class='card-text'>Status: " . $order['status'] . "</p>";

                                            // Fetch additional order details
                                            $sql_additional_details = "SELECT * FROM `order` WHERE order_id = '{$order['order_id']}'";
                                            $result_additional_details = mysqli_query($conn, $sql_additional_details);
                                            if (mysqli_num_rows($result_additional_details) > 0) {
                                                $additional_details = mysqli_fetch_assoc($result_additional_details);
                                                echo "<p class='card-text'>Total Amount: " . $additional_details['total_amount'] . "</p>";
                                                echo "<p class='card-text'>GCash Ref Number: " . $additional_details['gcash_ref_num'] . "</p>";
                                                echo "<p class='card-text'>GCash Name: " . $additional_details['gcash_account_name'] . "</p>";
                                                echo "<p class='card-text'>GCash Number: " . $additional_details['gcash_account_number'] . "</p>";
                                                echo "<p class='card-text'>Shipping Option: " . $order['shipping_company'] . "</p>";
                                                echo "<p class='card-text'>Receiver: " . ($additional_details['alternate_receiver'] ?: $order['fname']) . "</p>";
                                                echo "<p class='card-text'>Address: " . ($additional_details['alternate_address'] ?: $order['address']) . "</p>";
                                            } else {
                                                echo "<p>No additional details found.</p>";
                                            }

                                            echo "</div>";
                                            echo "</div>";
                                        }

                                        // Determine button based on status
                                        echo "<form method='post'>";
                                        echo "<input type='hidden' name='order_ref_number' value='" . $order_ref_number . "'>";
                                        if ($order_group[0]['status'] == 2) {
                                            echo "<button type='submit' name='confirm_order' class='btn btn-success'>Confirm</button>";
                                            echo "<button type='submit' name='cancel_order' class='btn btn-danger'>Cancel</button>";
                                        } elseif ($order_group[0]['status'] == 3) {
                                            echo "<button type='submit' name='ship_order' class='btn btn-primary'>Ship</button>";
                                            echo "<button type='submit' name='cancel_order' class='btn btn-danger'>Cancel</button>";
                                        } elseif ($order_group[0]['status'] == 4) {
                                            echo "<button type='submit' name='finish_order' class='btn btn-success'>Finish</button>";
                                        }
                                        echo "</form>";

                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>No orders found.</p>";
                                }
                                ?>
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
