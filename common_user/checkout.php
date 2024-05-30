<?php
include "../db.php";
session_start();

// Check if the user is logged in and has the appropriate category
if ($_SESSION['user_cat'] != 'U' || !isset($_SESSION['user_id'])) {
    header("location:../index.php");
    exit();
}

if (isset($_POST['selected_items'])) {
    // Retrieve selected items from $_POST
    $selected_items = $_POST['selected_items'];

    // Fetch user data from the database
    $s_user_id = $_SESSION['user_id'];
    $sql_user_info = "SELECT fname, address FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql_user_info);
    mysqli_stmt_bind_param($stmt, "s", $s_user_id);
    mysqli_stmt_execute($stmt);
    $result_user_info = mysqli_stmt_get_result($stmt);

    if ($result_user_info && mysqli_num_rows($result_user_info) > 0) {
        $user_info = mysqli_fetch_assoc($result_user_info);
        $fullname = $user_info['fname'];
        $address = $user_info['address'];
    } else {
        $fullname = "N/A";
        $address = "N/A";
    }

    // Prepare placeholders for the selected items
    $placeholders = implode(',', array_fill(0, count($selected_items), '?'));

    // Fetch selected items from the database
    $sql_selected_items = "
        SELECT i.item, i.price, i.item_img, o.quantity, o.date_added, o.order_id
        FROM `order` AS o
        JOIN `items` AS i ON o.item_id = i.item_id
        WHERE o.user_id = ? AND o.status = '1' AND o.order_id IN ($placeholders)
    ";
    $stmt = mysqli_prepare($conn, $sql_selected_items);
    $bindTypes = str_repeat("s", count($selected_items) + 1);
    $bindValues = array_merge([$s_user_id], $selected_items);
    mysqli_stmt_bind_param($stmt, $bindTypes, ...$bindValues);
    mysqli_stmt_execute($stmt);
    $result_selected_items = mysqli_stmt_get_result($stmt);

    $total_amount = 0.00;

    // Update the order_type to "P" for the selected items
    $sql_update_order_type = "
        UPDATE `order` 
        SET order_type = 'P' 
        WHERE user_id = ? AND order_id IN ($placeholders)
    ";
    $stmt_update = mysqli_prepare($conn, $sql_update_order_type);
    mysqli_stmt_bind_param($stmt_update, $bindTypes, ...$bindValues);
    mysqli_stmt_execute($stmt_update);

    // Update the stock in the items table for each selected item
    foreach ($selected_items as $order_id) {
        // Fetch the item_id and quantity for the selected order
        $sql_fetch_order = "SELECT item_id, quantity FROM `order` WHERE order_id = ?";
        $stmt_fetch_order = mysqli_prepare($conn, $sql_fetch_order);
        mysqli_stmt_bind_param($stmt_fetch_order, "s", $order_id);
        mysqli_stmt_execute($stmt_fetch_order);
        $result_fetch_order = mysqli_stmt_get_result($stmt_fetch_order);
        
        if ($result_fetch_order && mysqli_num_rows($result_fetch_order) > 0) {
            $row = mysqli_fetch_assoc($result_fetch_order);
            $item_id = $row['item_id'];
            $quantity = $row['quantity'];
            
            // Update the stock in the items table
            $sql_update_stock = "UPDATE items SET stocks = stocks - ? WHERE item_id = ?";
            $stmt_update_stock = mysqli_prepare($conn, $sql_update_stock);
            mysqli_stmt_bind_param($stmt_update_stock, "ss", $quantity, $item_id);
            mysqli_stmt_execute($stmt_update_stock);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <!-- Include the Select2 CSS file -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
        }
        .card {
            background-color: #212529;
            color: #ffffff;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .form-control {
            background-color: #454d55;
            color: #ffffff;
        }
        .form-select {
            background-color: #
            454d55;
            color: #ffffff;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px; /* Adjust the line height as needed */
        }

        .select2-container--default .select2-selection--single {
            height: 40px; /* Adjust the height as needed */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px; /* Adjust the height of the arrow icon */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            top: 50%; /* Adjust the position of the arrow icon */
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-8">
                <h3 class="text-center mt-3">Checkout Summary</h3>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Receiver Information</h5>
                        <p><strong>Full Name:</strong> <?php echo $fullname; ?></p>
                        <p><strong>Address:</strong> <?php echo $address; ?></p>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Selected Items</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_selected_items && mysqli_num_rows($result_selected_items) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_selected_items)) {
                                        $total_amount += ($row['price'] * $row['quantity']);
                                        ?>
                                        <tr>
                                            <input type="hidden" name="selected_item_ids[]" value="<?php echo $row['order_id']; ?>">
                                            <td><?php echo $row['item']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td>Php <?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No items selected</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Items Amount</h5>
                        <p><strong>Total:</strong> Php <?php echo number_format($total_amount, 2); ?></p>
                    </div>
                </div>
                <div class="card p-1 mt-3">
                    <h3 class="card-title">Other Information</h3>
                    <div class="card-body">
                        <div class='alert alert-light'>
                            Order Reference Number: <?php echo gen_order_ref_number(8); ?><br>
                        </div>
                        <form action="process_place_order.php" method="post">
                            <div class="mt-3">
                                <input type="hidden" name="f_total_amount" value="<?php echo $total_amount; ?>">
                                <label for="f_alt_receiver">Alternate Receiver Name:</label>
                                <input type="text" class="form-control mb-3" placeholder="This is Optional" name="f_alt_receiver">
                                <label for="f_alt_address">Ship to this Address:</label>
                                <input type="text" class="form-control mb-3" placeholder="This is Optional" name="f_alt_address">
                                <label for="f_payment_method" class="form-label">Payment Method:</label>
                                <select name="f_payment_method" class="form-select mb-3" id="f_payment_method">
                                    <?php
                                    // Payment methods and their corresponding image URLs
                                    $payment_methods = array(
                                        "GCash" => array("payment_method_id" => 1, "image_url" => "../img/gcash.png")
                                    );

                                    // Loop through each payment method and its image URL
                                    foreach ($payment_methods as $method => $data) { ?>
                                        <option value="<?php echo htmlspecialchars($data['payment_method_id']); ?>" data-image="<?php echo htmlspecialchars($data['image_url']); ?>">
                                            <?php echo htmlspecialchars($method); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <label for="f_ship_option">Shipping Options:</label>
                                <select name="f_ship_option" id="f_ship_option" class="form-select mb-2">
                                    <?php
                                    // Shipping companies and their corresponding image URLs
                                    $shipping_companies = array(
                                        "Flash Express" => array("shipper_id" => 1, "image_url" => "../img/flash.jpg"),
                                        "J&T Express" => array("shipper_id" => 2, "image_url" => "../img/jnt.jpg")
                                    );

                                    // Loop through each shipping company and its image URL
                                    foreach ($shipping_companies as $company => $data) { ?>
                                        <option value="<?php echo htmlspecialchars($data['shipper_id']); ?>" data-image="<?php echo htmlspecialchars($data['image_url']); ?>">
                                            <?php echo htmlspecialchars($company); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <div id="selectedImage"></div> <!-- Container to display selected image -->
                                <input type="hidden" name="f_order_ref_number" value="<?php echo gen_order_ref_number(8); ?>">
                                <input type="submit" value="Place Order" class="btn btn-warning">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery before Select2 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include the Select2 JS file -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for shipping options dropdown
            $('#f_ship_option').select2({
                templateResult: formatCompany, // Custom function to format each option
                templateSelection: formatCompany // Custom function to format the selected option
            });

            // Function to format each option
            function formatCompany(option) {
                if (!option.id) {
                    return option.text;
                }

                var imageUrl = $(option.element).data('image');
                if (!imageUrl) {
                    return option.text;
                }

                var $company = $(
                    '<span><img src="' + imageUrl + '" class="img-flag" style="max-width: 80px; max-height: 80px;" /> ' + option.text + '</span>'
                );
                return $company;
            }

// Initialize Select2 for payment method dropdown
$('#f_payment_method').select2({
    templateResult: formatPaymentMethod, // Custom function to format each option
    templateSelection: formatPaymentMethod // Custom function to format the selected option
});

// Function to format each option
function formatPaymentMethod(option) {
    if (!option.id) {
        return option.text;
    }

    var imageUrl = $(option.element).data('image');
    if (!imageUrl) {
        return option.text;
    }

    var $paymentMethod = $(
        '<span><img src="' + imageUrl + '" class="img-flag" style="max-width: 80px; max-height: 80px;" /> ' + option.text + '</span>'
    );
    return $paymentMethod;
}
});
</script>
</body>
</html>
<?php
} else {
// Redirect back to cart if no items are selected
header("location: index.php?msg=No items selected for checkout.");
exit();
}

function gen_order_ref_number($length = 8) {
// Generate a random order reference number
$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$ref_number = '';
for ($i = 0; $i < $length; $i++) {
$ref_number .= $characters[rand(0, strlen($characters) - 1)];
}
return $ref_number;
}
?>
