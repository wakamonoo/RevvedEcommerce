<?php
include_once "../db.php";
session_start();

if($_SESSION['user_cat'] != 'U'){
    header("location: ../index.php");
}

$user_id = $_SESSION['user_id'];

// Fetch items with status = 2 (Pending)
$sql_pending = "SELECT 
    o.`order_ref_number`, 
    GROUP_CONCAT(i.`item` SEPARATOR ', ') AS `items`, 
    GROUP_CONCAT(i.`item_img` SEPARATOR ', ') AS `item_imgs`, 
    o.`gcash_amount_sent`, 
    CASE WHEN o.`alternate_receiver` IS NOT NULL AND o.`alternate_receiver` != '' THEN '' ELSE u.`fname` END AS `fname`, 
    CASE WHEN o.`alternate_address` IS NOT NULL AND o.`alternate_address` != '' THEN '' ELSE u.`address` END AS `address`,
    pm.`payment_method_desc`,
    s.`shipping_company`,
    o.`alternate_receiver`,
    o.`alternate_address`
FROM `order` o
JOIN `items` i ON o.`item_id` = i.`item_id`
JOIN `users` u ON o.`user_id` = u.`user_id`
JOIN `payment_method` pm ON o.`payment_method` = pm.`payment_method_id`
JOIN `shippers` s ON o.`shipper_id` = s.`shipper_id`
WHERE o.`user_id` = '$user_id' AND o.`status` = 2
GROUP BY o.`order_ref_number`, pm.`payment_method_desc`, s.`shipping_company`, o.`alternate_receiver`, o.`alternate_address`";

$result_pending = mysqli_query($conn, $sql_pending);

// Fetch items with status = 3 (To Ship)
$sql_to_ship = "SELECT 
o.`order_ref_number`, 
GROUP_CONCAT(i.`item` SEPARATOR ', ') AS `items`, 
GROUP_CONCAT(i.`item_img` SEPARATOR ', ') AS `item_imgs`, 
o.`gcash_amount_sent`, 
CASE WHEN o.`alternate_receiver` IS NOT NULL AND o.`alternate_receiver` != '' THEN '' ELSE u.`fname` END AS `fname`, 
CASE WHEN o.`alternate_address` IS NOT NULL AND o.`alternate_address` != '' THEN '' ELSE u.`address` END AS `address`,
pm.`payment_method_desc`,
s.`shipping_company`,
o.`alternate_receiver`,
o.`alternate_address`
FROM `order` o
JOIN `items` i ON o.`item_id` = i.`item_id`
JOIN `users` u ON o.`user_id` = u.`user_id`
JOIN `payment_method` pm ON o.`payment_method` = pm.`payment_method_id`
JOIN `shippers` s ON o.`shipper_id` = s.`shipper_id`
WHERE o.`user_id` = '$user_id' AND o.`status` = 3
GROUP BY o.`order_ref_number`, pm.`payment_method_desc`, s.`shipping_company`, o.`alternate_receiver`, o.`alternate_address`";

$result_to_ship = mysqli_query($conn, $sql_to_ship);

// Fetch items with status = 4 (Shipping)
$sql_shipping = "SELECT 
    o.`order_ref_number`, 
    GROUP_CONCAT(i.`item` SEPARATOR ', ') AS `items`, 
    GROUP_CONCAT(i.`item_img` SEPARATOR ', ') AS `item_imgs`, 
    o.`gcash_amount_sent`, 
    CASE WHEN o.`alternate_receiver` IS NOT NULL AND o.`alternate_receiver` != '' THEN '' ELSE u.`fname` END AS `fname`, 
    CASE WHEN o.`alternate_address` IS NOT NULL AND o.`alternate_address` != '' THEN '' ELSE u.`address` END AS `address`,
    pm.`payment_method_desc`,
    s.`shipping_company`,
    o.`alternate_receiver`,
    o.`alternate_address`
FROM `order` o
JOIN `items` i ON o.`item_id` = i.`item_id`
JOIN `users` u ON o.`user_id` = u.`user_id`
JOIN `payment_method` pm ON o.`payment_method` = pm.`payment_method_id`
JOIN `shippers` s ON o.`shipper_id` = s.`shipper_id`
WHERE o.`user_id` = '$user_id' AND o.`status` = 4
GROUP BY o.`order_ref_number`, pm.`payment_method_desc`, s.`shipping_company`, o.`alternate_receiver`, o.`alternate_address`";

$result_shipping = mysqli_query($conn, $sql_shipping);

// Fetch items with status = 5 (Completed)
$sql_completed = "SELECT 
    o.`order_ref_number`, 
    GROUP_CONCAT(i.`item` SEPARATOR ', ') AS `items`, 
    GROUP_CONCAT(i.`item_img` SEPARATOR ', ') AS `item_imgs`, 
    GROUP_CONCAT(o.`gcash_amount_sent` SEPARATOR ', ') AS `gcash_amount_sent`
FROM `order` o
JOIN `items` i ON o.`item_id` = i.`item_id`
WHERE o.`user_id` = '$user_id' AND o.`status` = 5
GROUP BY o.`order_ref_number`";

$result_completed = mysqli_query($conn, $sql_completed);

// Fetch items with status = 0 (Cancelled)
$sql_cancelled = "SELECT 
    o.`order_ref_number`, 
    GROUP_CONCAT(i.`item` SEPARATOR ', ') AS `items`, 
    GROUP_CONCAT(i.`item_img` SEPARATOR ', ') AS `item_imgs`, 
    GROUP_CONCAT(o.`gcash_amount_sent` SEPARATOR ', ') AS `gcash_amount_sent`
FROM `order` o
JOIN `items` i ON o.`item_id` = i.`item_id`
WHERE o.`user_id` = '$user_id' AND o.`status` = 0
GROUP BY o.`order_ref_number`";

$result_cancelled = mysqli_query($conn, $sql_cancelled);

// Fetch items with reviews submitted
$sql_reviews = "SELECT `order_ref_number` FROM `reviews` WHERE `user_id` = '$user_id'";
$result_reviews = mysqli_query($conn, $sql_reviews);
$reviewed_items = [];
while ($row_review = mysqli_fetch_assoc($result_reviews)) {
    $reviewed_items[] = $row_review['order_ref_number'];
}
// Query to get the number of items in the cart for the logged-in user
$cart_count_result = mysqli_query($conn, "SELECT COUNT(*) AS cart_count FROM `order` WHERE status = 1 AND user_id = '$user_id'");
$cart_count_row = mysqli_fetch_assoc($cart_count_result);
$cart_count = $cart_count_row['cart_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .card {
            margin-top: 60px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s ease-in-out;
            width: 800px;
            text-align: center;
            background-color: #212529;
        }
        .card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
        }
        .card-text {
            font-size: 14px;
            color: #ffffff;
        }
        .card-img-top {
            width: 200px;
            height: 200px;
            cursor: pointer;
            display: flex;
            margin: 0 auto;
        }
        .btn-group {
            border-right: 4px solid white; /* Add white partition */
        }
        .btn-group .btn:last-child {
            border-right: none; /* Remove partition for the last button */
        }
        .btn-group .btn img {
            max-width: 50px; /* Adjust the maximum width of the images */
            max-height: 50px; /* Adjust the maximum height of the images */
            margin-right: 5px;
}
.btn-group .btn {
border-right: 2px solid white; / Add vertical line */
}
.review-submitted {
    color: #007bff; /* Change the color to match the button group */
}
.track{
    margin-top: 50px;
    text-transform: uppercase; /* Convert text to uppercase */
    font-family: 'Montserrat', sans-serif; /* Applies a different font to the username */
}

.badge {
    color: blue;
    padding: 0; /* Remove padding */
    border-radius: 50%; /* Make it circular */
    position: absolute;
    display: flex;
    justify-content: center;
    right: 198px;
    top: 18px;*/
    font-size: 14px; /* Adjust font size */
    line-height: 1; /* Adjust line height */
}

.search-container {
    position: absolute;
    display: flex;
    justify-content: center;
    right: 250px;
    top: 15px;
}

.search-form {
    display: flex;
    align-items: center;
}

.search-input {
    padding: 0.4rem 0.8rem; /* Adjust padding */
    border: 1px solid #ccc;
    margin-right: 1px;
    font-size: 14px; /* Adjust font size */
}

.search-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 0.4rem 1rem; /* Adjust padding */
    cursor: pointer;
    font-size: 14px; /* Adjust font size */
}

.search-button:hover {
    background-color: #0056b3;
}
.dropdown {
            position: absolute;
    right: 20px; /* Align to the right of the page */
    top: 5px; /* Optional: Align to the top of the page */
    display: inline-block;
    font-family: Arial, sans-serif;
    font-weight: bold; /* Make the text bold */
    text-transform: uppercase; /* Convert text to all caps */
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: none; /* Remove the border */
    padding: 0.375rem 0.75rem;
    font-size: 1.2rem; /* Increase font size */
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
    color: #fff;
    text-decoration: none; /* Remove underline */
}
.dropdown:hover .dropdown-toggle {
    color: #343a40 !important; /* Change text color on hover */
}

.dropdown-toggle {
    color: white;
    text-transform: uppercase;
    padding: 10px 20px;
    background-color: transparent;
    border: none;
    cursor: pointer;
    outline: none;
    font-weight: bold;
    font-size: 1.2rem; 
}

.dropdown-menu {
    position: absolute;
    right: 20px; /* Align to the right of the page */
    display: none;
    position: absolute;
    background-color: #212529;
    border: 1px solid #ccc;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 10px;
    z-index: 1000;
}

.dropdown-menu a {
    display: block;
    padding: 8px 0;
    color: #fff;
    text-decoration: none;
}

.dropdown-menu a:hover {
    color: #343a40 !important;; /* Change text color on hover */
}
.cart {
    position: absolute;
    display: flex;
    justify-content: center;
    right: 200px;
    top: 25px;
}

.cart img {
    transition: filter 0.15s ease;
}

.cart img:hover {
    filter: brightness(40%) saturate(10%) contrast(180%);
}
.toolbar{
    background-color: #dc3545;
    width: 100%;
    margin-top: -10px;
    height: 86px;
}
.home {
    position: absolute;
    display: flex;
    justify-content: center;
    left: 25px;
    top: 5px;
}

.home img {
    transition: filter 0.15s ease;
}

.home img:hover {
    filter: brightness(40%) saturate(10%) contrast(180%);
}
.tag {
  text-transform: uppercase; /* Transforms the text to uppercase */
  font-size: 1rem; /* Sets the font size */
  position: absolute;
  display: flex;
  flex-direction: column; /* Stack words vertically */
  align-items: right; /* Center the words horizontally */
  left: 180px;
  top: 15px;
}

.tag .line {
  display: block; /* Stacks the words vertically */
  margin: 0; /* Removes vertical spacing between words */
}

.tag .username {
  font-size: 2rem; /* Sets a different font size for the username */
  font-weight: 700; /* Sets the font weight to bold */
  font-family: 'Montserrat', sans-serif; /* Applies a different font to the username */
  margin: 0; /* Removes vertical spacing */
}
</style>

</head>
<body>
<div class="toolbar">
<a href="index.php" class="home">
        <img src="../img/logo.png" alt="Home" style="width: 150px; height: 60px;">
    </a>
    <h3 class="tag">Shop and Rev Up <span class="username"><?php echo $_SESSION['username']; ?></span></h3>
<div class="search-container">
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search...">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <a href="cart.php" class="cart">
        <img src="../img/cart.png" alt="Cart Icon" style="width: 16px; height: 16px;">
    </a>
    <span class="badge badge-light"><?php echo $cart_count; ?></span>
    <div class="dropdown">
    <button class="dropdown-toggle" id="dropdownMenuButton">
        <img src="../uploads/<?php echo $_SESSION['user_img']; ?>" alt="Pic" style="width: 30px; height: 30px; border-radius: 50%;">
        <?php echo $_SESSION['username']; ?>
    </button>
        <div class="dropdown-menu" id="dropdownMenu">
            <a href="information.php">Personal Information</a>
            <a href="tracking.php">Orders Tracking</a>
            <div class="dropdown-divider"></div>
            <a href="?logout">Logout</a>
        </div>
    </div>
</div>
</div>
<div class="container">
<h1 class="track">Order Tracking</h1>
<!-- Button group for filtering -->
<div class="btn-group" role="group" aria-label="Order Status">
    <button type="button" class="btn btn-primary" onclick="showItems('pending')">
        <img src="../img/pending.png" alt="Pending">PENDING
    </button>
    <button type="button" class="btn btn-primary" onclick="showItems('to_ship')">
        <img src="../img/to_ship.png" alt="To Ship">TO SHIP
    </button>
    <button type="button" class="btn btn-primary" onclick="showItems('shipping')">
        <img src="../img/shipping.png" alt="Shipping">SHIPPING
    </button>
    <button type="button" class="btn btn-primary" onclick="showItems('completed')">
        <img src="../img/completed.png" alt="Completed">COMPLETED
    </button>
    <button type="button" class="btn btn-primary" onclick="showItems('cancelled')">
        <img src="../img/cancel.png" alt="Cancelled">CANCELLED
    </button>
</div>

<!-- Item cards -->
<div class="row" id="itemContainer">
    <?php while($row = mysqli_fetch_assoc($result_pending)): ?>
        <div class="col-lg-12 col-md-12 col-sm-12 item pending" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Reference Number: <?php echo $row['order_ref_number']; ?></h5>
                    <p class="card-text">GCash Amount Sent: <?php echo $row['gcash_amount_sent']; ?></p>

                    <?php 
                    // Split concatenated item details
                    $items = explode(', ', $row['items']);
                    $item_imgs = explode(', ', $row['item_imgs']);
                    foreach($items as $index => $item): 
                    ?>
                        <div class="item-detail">
                            <img src="../images/<?php echo $item_imgs[$index]; ?>" class="card-img-top" alt="Item Image">
                            <p class="card-text"><?php echo $item; ?></p>
                        </div>
                    <?php endforeach; ?>

                    <p class="card-text">Receiver: <?php echo !empty($row['alternate_receiver']) ? $row['alternate_receiver'] : $row['fname']; ?></p>
                    <p class="card-text">Address: <?php echo !empty($row['alternate_address']) ? $row['alternate_address'] : $row['address']; ?></p>
                    <p class="card-text">Payment: <?php echo $row['payment_method_desc']; ?></p>
                    <p class="card-text">Shipping: <?php echo $row['shipping_company']; ?></p>
                    
                    <!-- Add cancel button -->
                    <form action="process_cancel_order.php" method="POST">
                        <input type="hidden" name="order_ref_number" value="<?php echo $row['order_ref_number']; ?>">
                        <button type="submit" class="btn btn-danger">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <?php while($row = mysqli_fetch_assoc($result_to_ship)): ?>
        <div class="col-lg-12 col-md-12 col-sm-12 item to_ship" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Reference Number: <?php echo $row['order_ref_number']; ?></h5>
                    <p class="card-text">GCash Amount Sent: <?php echo $row['gcash_amount_sent']; ?></p>

                    <?php 
                    // Split concatenated item details
                    $items = explode(', ', $row['items']);
                    $item_imgs = explode(', ', $row['item_imgs']);
                    foreach($items as $index => $item): 
                    ?>
                        <div class="item-detail">
                            <img src="../images/<?php echo $item_imgs[$index]; ?>" class="card-img-top" alt="Item Image">
                            <p class="card-text"><?php echo $item; ?></p>
                        </div>
                    <?php endforeach; ?>

                    <p class="card-text">Receiver: <?php echo !empty($row['alternate_receiver']) ? $row['alternate_receiver'] : $row['fname']; ?></p>
                    <p class="card-text">Address: <?php echo !empty($row['alternate_address']) ? $row['alternate_address'] : $row['address']; ?></p>
                    <p class="card-text">Payment: <?php echo $row['payment_method_desc']; ?></p>
                    <p class="card-text">Shipping: <?php echo $row['shipping_company']; ?></p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>


    <?php while($row = mysqli_fetch_assoc($result_shipping)): ?>
        <div class="col-lg-12 col-md-12 col-sm-12 item shipping" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Reference Number: <?php echo $row['order_ref_number']; ?></h5>
                    <p class="card-text">GCash Amount Sent: <?php echo $row['gcash_amount_sent']; ?></p>

                    <?php 
                    // Split concatenated item details
                    $items = explode(', ', $row['items']);
                    $item_imgs = explode(', ', $row['item_imgs']);
                    foreach($items as $index => $item): 
                    ?>
                        <div class="item-detail">
                            <img src="../images/<?php echo $item_imgs[$index]; ?>" class="card-img-top" alt="Item Image">
                            <p class="card-text"><?php echo $item; ?></p>
                        </div>
                    <?php endforeach; ?>

                    <p class="card-text">Receiver: <?php echo !empty($row['alternate_receiver']) ? $row['alternate_receiver'] : $row['fname']; ?></p>
                    <p class="card-text">Address: <?php echo !empty($row['alternate_address']) ? $row['alternate_address'] : $row['address']; ?></p>
                    <p class="card-text">Payment: <?php echo $row['payment_method_desc']; ?></p>
                    <p class="card-text">Shipping: <?php echo $row['shipping_company']; ?></p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>


    <?php while($row = mysqli_fetch_assoc($result_completed)): ?>
        <div class="col-lg-12 col-md-12 col-sm-12 item completed" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Reference Number: <?php echo $row['order_ref_number']; ?></h5>
                    <p class="card-text">GCash Amount Sent: <?php echo $row['gcash_amount_sent']; ?></p>

                    <?php 
                    // Split concatenated item details
                    $items = explode(', ', $row['items']);
                    $item_imgs = explode(', ', $row['item_imgs']);
                    foreach($items as $index => $item): 
                    ?>
                        <div class="item-detail">
                            <img src="../images/<?php echo $item_imgs[$index]; ?>" class="card-img-top" alt="Item Image">
                            <h6 class="card-title"><?php echo $item; ?></h6>
                        </div>
                    <?php endforeach; ?>

                    <?php if (in_array($row['order_ref_number'], $reviewed_items)): ?>
                        <p class="card-text review-submitted">Review Submitted</p>
                    <?php else: ?>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary add-review-btn" data-order-ref="<?php echo $row['order_ref_number']; ?>">
                            Add Review
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php while($row = mysqli_fetch_assoc($result_cancelled)): ?>
    <div class="col-lg-12 col-md-12 col-sm-12 item cancelled" style="display: none;">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Order Reference Number: <?php echo $row['order_ref_number']; ?></h5>
                <p class="card-text">GCash Amount Sent: <?php echo $row['gcash_amount_sent']; ?></p>

                <?php 
                // Split concatenated item details
                $items = explode(', ', $row['items']);
                $item_imgs = explode(', ', $row['item_imgs']);
                foreach($items as $index => $item): 
                ?>
                    <div class="item-detail">
                        <img src="../images/<?php echo $item_imgs[$index]; ?>" class="card-img-top" alt="Item Image">
                        <h6 class="card-title"><?php echo $item; ?></h6>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
</div>

<script>
    function showItems(status) {
        // Hide all item cards
        var items = document.getElementsByClassName('item');
        for (var i = 0; i < items.length; i++) {
            items[i].style.display = 'none';
        }

        // Show item cards with the selected status
        var selectedItems = document.getElementsByClassName(status);
        for (var j = 0; j < selectedItems.length; j++) {
            selectedItems[j].style.display = 'block';
        }
    }
    
    // Add event listener to all buttons with the 'add-review-btn' class
    var addReviewButtons = document.querySelectorAll('.add-review-btn');
    addReviewButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Get the order reference number from the button's data-order-ref attribute
            var orderRefNumber = button.getAttribute('data-order-ref');
            // Redirect to review.php with the order reference number as a query parameter
            window.location.href = 'review.php?order_ref_number=' + orderRefNumber;
        });
    });
// JavaScript code
document.addEventListener('DOMContentLoaded', function() {
    var dropdownToggle = document.getElementById('dropdownMenuButton');
    var dropdownMenu = document.getElementById('dropdownMenu');

    dropdownToggle.addEventListener('click', function() {
        if (dropdownMenu.style.display === 'block') {
            dropdownMenu.style.display = 'none';
        } else {
            dropdownMenu.style.display = 'block';
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
});
</script>
</body>
</html>


