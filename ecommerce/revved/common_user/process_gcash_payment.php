
<?php
include_once "../db.php";
session_start();

if(isset($_POST['f_order_ref_number'])) {
    $ord_ref_num = mysqli_real_escape_string($conn, $_POST['f_order_ref_number']);
    $user_id = $_SESSION['user_id'];
    $alt_rec = isset($_POST['f_alt_receiver']) ? mysqli_real_escape_string($conn, $_POST['f_alt_receiver']) : '';
    $alt_add = isset($_POST['f_alt_address']) ? mysqli_real_escape_string($conn, $_POST['f_alt_address']) : '';
    $shipper_id = isset($_POST['f_shipper_id']) ? mysqli_real_escape_string($conn, $_POST['f_shipper_id']) : '';
    $payment_method = mysqli_real_escape_string($conn, $_POST['f_payment_method']);
    $gcash_ref_num = mysqli_real_escape_string($conn, $_POST['f_gcash_ref_num']);
    $gcash_acc_name = mysqli_real_escape_string($conn, $_POST['f_gcash_acc_name']);
    $gcash_acc_num = mysqli_real_escape_string($conn, $_POST['f_gcash_acc_num']);
    $gcash_amt_sent = mysqli_real_escape_string($conn, $_POST['f_gcash_amt_sent']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['f_total_amount']);

    // Validate required fields
    if(empty($ord_ref_num) || empty($user_id) || empty($payment_method) || empty($gcash_ref_num) || empty($gcash_acc_name) || empty($gcash_acc_num) || empty($gcash_amt_sent) || empty($total_amount)){
        header("location: index.php?msg=Required fields are missing.");
        exit();
    }

    // Check if GCash amount sent is sufficient
    if($total_amt_to_pay > $gcash_amt_sent){
        header("location: index.php?msg=Amount is Insufficient.");
        exit();
    }

    // Update the order in the database
    $sql_update_order = "UPDATE `order`
                            SET `status` = 2,
                                `order_ref_number` = '$ord_ref_num',
                                `payment_method` = '$payment_method',
                                `alternate_receiver` = '$alt_rec',
                                `alternate_address` = '$alt_add',
                                `shipper_id` = '$shipper_id',
                                `gcash_ref_num` = '$gcash_ref_num',
                                `gcash_account_name` = '$gcash_acc_name',
                                `gcash_account_number` = '$gcash_acc_num',
                                `gcash_amount_sent` = '$gcash_amt_sent',
                                `total_amount` = '$total_amount'
                          WHERE `user_id` = '$user_id' 
                            AND `status` = '1'
                            AND `order_type` = 'P'";

    $execute_update_order = mysqli_query($conn, $sql_update_order);

    if($execute_update_order) {
        // Update successful, now let's process the selected items
        if(isset($_POST['selected_items'])) {
            $selected_item_ids = $_POST['selected_items'];
            foreach($selected_item_ids as $order_id) {
                // Sanitize the order ID to prevent SQL injection
                $order_id = mysqli_real_escape_string($conn, $order_id);
                
                // Construct your SQL query to update the selected item
                $sql_update_item = "UPDATE `order` SET `status` = '2' WHERE `order_id` = '$order_id'";
                
                // Execute the update query
                $result_update_item = mysqli_query($conn, $sql_update_item);
            }
        }
        header("location: cart.php?msg=1");
        exit();
    } else {
        header("location: cart.php?msg=2");
        exit();
    }
}
?>

