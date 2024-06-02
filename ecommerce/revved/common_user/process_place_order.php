<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #343a40;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 80%;
        }
        .card {
            background-color: #212529;
            color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            color: #dc3545;
        }
        .form-control {
            background-color: #212529;
            border: 1px solid #dc3545;
            color: #fff;
            border-radius: 5px;
        }
        .form-control:focus {
            background-color: #212529;
            border: 1px solid #dc3545;
            color: #fff;
            box-shadow: none;
        }
        .btn-primary {
            background-color: #dc3545;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #a71d2a;
        }
        .form-header {
            border-bottom: 1px solid #dc3545;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php
include_once "../db.php";
session_start();

if(isset($_POST['f_payment_method'])){
    $payment_method = $_POST['f_payment_method'];
    $order_ref_number = $_POST['f_order_ref_number'];
    $user_id = $_SESSION['user_id'];
    $alternate_receiver = isset($_POST['f_alt_receiver']) ? mysqli_real_escape_string($conn, $_POST['f_alt_receiver']) : '';
    $alternate_address = isset($_POST['f_alt_address']) ? mysqli_real_escape_string($conn, $_POST['f_alt_address']) : '';
    $shipper_id = isset($_POST['f_ship_option']) ? mysqli_real_escape_string($conn, $_POST['f_ship_option']) : '';
    $total_amount = isset($_POST['f_total_amount']) ? mysqli_real_escape_string($conn, $_POST['f_total_amount']) : '';

    // Fetch shipping cost based on shipper_id
    $sql_get_shipping_cost = "SELECT shipping_cost FROM shippers WHERE shipper_id = '$shipper_id'";
    $result_get_shipping_cost = mysqli_query($conn, $sql_get_shipping_cost);
    if($result_get_shipping_cost && mysqli_num_rows($result_get_shipping_cost) > 0) {
        $shipping_cost_row = mysqli_fetch_assoc($result_get_shipping_cost);
        $shipping_cost = $shipping_cost_row['shipping_cost'];

        // Add shipping cost to the total amount
        $total_amount += $shipping_cost;
    } else {
        // Handle error if shipping cost retrieval fails
        echo "Error fetching shipping cost.";
        exit; // Exit script
    }

    // Check if payment method is GCash
    if($payment_method == "1"){ 
        // Display form to input GCash payment details
        ?>
        <div class="container">
    <div class="card p-4">
        <h3 class="form-header">Input GCash Payment Details</h3>
            <form action="process_gcash_payment.php" method="POST">
            Total Amount to Pay: <b><?php echo "Php " . number_format($total_amount, 2); ?></b> <br>
            Please pay EXACT AMOUNT to this GCash Account Number: 09283137101<br>
            Account Name: Joven Bataller<br>
            <!-- Add QR code image below account name -->
            <div class="mb-3">
                <label for="" class="form-label">QR Code</label><br>
                <img src="../img/qr_code.jpg" alt="QR Code" style="max-width: 200px;">
            </div>
                <hr>
                <input type="text" hidden name="f_total_amount" value="<?php echo $total_amount; ?>" />
                <input type="text" hidden name="f_payment_method" value="<?php echo $payment_method; ?>" />
                <input type="text" hidden name="f_order_ref_number" value="<?php echo $order_ref_number; ?>" />
                <input type="text" hidden name="f_alt_receiver" value="<?php echo $alternate_receiver; ?>" />
                <input type="text" hidden name="f_alt_address" value="<?php echo $alternate_address; ?>" />
                <input type="text" hidden name="f_shipper_id" value="<?php echo $shipper_id; ?>" />
                <div class="mb-3">
                    <label for="" class="form-label">GCash Reference Number</label>
                    <input type="text" class="form-control" name="f_gcash_ref_num">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">GCash Account Sender Name</label>
                    <input type="text" class="form-control" name="f_gcash_acc_name">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">GCash Account Number</label>
                    <input type="text" class="form-control" name="f_gcash_acc_num">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">GCash Amount Sent</label>
                    <input type="text" class="form-control" name="f_gcash_amt_sent">
                </div>
                <input type="submit" value="Save" class="btn btn-primary">
            </form>
        </div>
        <?php 
        die();                            
    }

    // If payment method is not GCash, update the order in the database
    $sql_update_order = "UPDATE `order`
                         SET `payment_method` = '$payment_method',
                             `order_ref_number` = '$order_ref_number',
                             `alternate_receiver` = '$alternate_receiver',
                             `alternate_address` = '$alternate_address',
                             `shipper_id` = '$shipper_id',
                             `total_amount` = '$total_amount'
                         WHERE `user_id` = '$user_id' 
                         AND `order_phase` = '1'";

    $execute_update_order = mysqli_query($conn, $sql_update_order);
    
    if($execute_update_order == 1){
        header("location: index.php?page=home&msg=1");
    } else {
        header("location: index.php?page=home&msg=2");
    }
    // Fetch all orders with order_type 'P'
$sql_get_orders = "
SELECT item_id, quantity
FROM `order`
WHERE order_type = 'P' AND status = 2
";
$result_get_orders = mysqli_query($conn, $sql_get_orders);

if ($result_get_orders) {
// Process each order
while ($row = mysqli_fetch_assoc($result_get_orders)) {
    $item_id = $row['item_id'];
    $quantity = $row['quantity'];

    // Subtract quantity from the stocks of the corresponding item
    $sql_update_stocks = "UPDATE `items` SET stocks = stocks - $quantity WHERE item_id = $item_id";
    $result_update_stocks = mysqli_query($conn, $sql_update_stocks);

    if (!$result_update_stocks) {
        // Handle error if update fails
        echo "Error updating stock for item ID $item_id.";
    }
}
} else {
// Handle error if query fails
echo "Error fetching orders.";
}

}
?>
</div>
</div>
</body>
</html>
