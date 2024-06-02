<?php
include "../db.php";
session_start();
$s_user_id = $_SESSION['user_id'];
if ($_SESSION['user_cat'] != 'U') {
    header("location:../index.php");
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("location:../index.php");
    die();
}

if (isset($_GET['delete_from_cart'])) {
    $order_id = $_GET['delete_from_cart'];
    $sql_delete_from_cart = "DELETE FROM `order` WHERE `order_id` = '$order_id'";
    $sql_execute = mysqli_query($conn, $sql_delete_from_cart);
    if ($sql_execute) {
        header("location: index.php?msg=cart_item_removed");
    }
}

function gen_order_ref_number($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_string;
}

// Check if the form is submitted and selected items are set in $_POST
if (isset($_POST['selected_items'])) {
    // Retrieve selected items from $_POST
    $selected_items = $_POST['selected_items'];
    
    // Store selected items in the session
    $_SESSION['selected_items'] = $selected_items;
}

// Fetch user data from the database
$sql = "SELECT fname, address FROM users WHERE user_id = '$s_user_id'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful and if it returned any rows
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the first row of the result set
    $row = mysqli_fetch_assoc($result);
    
    // Assign user data to variables
    $fullname = $row['fname']; 
    $address = $row['address']; 
    
    // Store the user's full name and address in session variables
    $_SESSION['fname'] = $fullname;
    $_SESSION['address'] = $address;
} else {
    // Handle the case when the user data couldn't be fetched
    // You might want to display an error message or redirect the user
    echo "Error: Unable to fetch user data from the database.";
}
// Query to get the number of items in the cart for the logged-in user
$cart_count_result = mysqli_query($conn, "SELECT COUNT(*) AS cart_count FROM `order` WHERE status = 1 AND user_id = '$s_user_id'");
$cart_count_row = mysqli_fetch_assoc($cart_count_result);
$cart_count = $cart_count_row['cart_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Common User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            padding-top: 0px;
            font-family: Arial, sans-serif;
        }

        .btn-link {
            color: #dc3545 !important;
            /* Red color for logout link */
        }

        .container-fluid {
            padding-top: 50px;
        }

        .table {
            background-color: #212529;
            /* Darker gray background for tables */
            color: #ffffff;
            /* White text for tables */
        }

        .btn-primary {
            background-color: #007bff;
            /* Blue button */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Dark blue hover */
            border-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red button */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            /* Dark red hover */
            border-color: #bd2130;
        }

        .btn-warning {
            background-color: #ffc107;
            /* Yellow button */
            border-color: #ffc107;
            height: 50px;
            margin-top: -8px;
            margin-bottom: -8px;
            border-radius: 0;
            width: 150px;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            /* Dark yellow hover */
            border-color: #d39e00;
        }

        .display-3,
        .display-6 {
            color: #ffffff;
            /* White text for headings */
        }

        .form-control {
            background-color: #454d55;
            /* Darker gray form input background */
            color: #ffffff;
            /* White text for form input */
        }

        hr {
            border-top-color: #6c757d;
            /* Gray border for HR */
        }

        .item-image {
            width: 50px;
            /* Adjust width as needed */
            height: auto;
            /* Maintain aspect ratio */
        }

        .navbar {
            margin-top: -15px;
            background-color: #212529 !important;
            /* Adjust margin for better spacing */
        }

        .form-check-input {
            margin-left: 10px;
            margin-top: 0px;
        }

        .form-check-label {
            margin-left: -7%;
            
        }
        .col-6{
            width: 80%;
        }
        .total {
    margin-right: 5%;
    width: 500px; /* Fixed width */
    display: inline-block; /* Display as inline-block */
    text-align: right; /* Align text to the right */
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
<div class="d-flex justify-content-end mb-3">
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
        <img src="../uploads/<?php echo $_SESSION['user_img']; ?>" alt="User Image" style="width: 30px; height: 30px; border-radius: 50%;">
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
<body>
    <div class="container-fluid mx-auto"> <!-- Added mx-auto class to center the container -->
        <div class="row justify-content-center">
            <div class="col-6">
                <h6 class="display-6 text-center">Cart</h6>
                <?php
                // Assuming $conn is your database connection and $s_user_id is the session user ID
                $sql_get_cart_items = "
                    SELECT i.item, i.price, i.item_img, o.quantity, o.date_added, o.order_id, i.item_status
                    FROM `order` AS o
                    JOIN `items` AS i ON o.item_id = i.item_id
                    WHERE o.user_id='$s_user_id' 
                    AND o.status='1'
                    AND i.stocks > 0
                    AND i.item_status = 'A'
                    AND `order_type` = 'individual'";

                $cart_results = mysqli_query($conn, $sql_get_cart_items);

                if (mysqli_num_rows($cart_results) > 0) {
                    echo "<form action='checkout.php' method='post'>";
                    echo "<table class='table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Select</th>";
                    echo "<th>Image</th>";
                    echo "<th>Item</th>";
                    echo "<th>Quantity</th>";
                    echo "<th>Total Price</th>";
                    echo "<th>Action</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($cart = mysqli_fetch_assoc($cart_results)) { ?>
                    <tr>
                    <td><input type="checkbox" name="selected_items[]" value="<?php echo $cart['order_id']; ?>"></td>
                    <td><img src="<?php echo $cart['item_img']; ?>" alt="Item Image" class="item-image"></td>
                    <td><?php echo $cart['item']; ?></td>
                    <td><?php echo $cart['quantity'] . " pcs"; ?></td>
                    <td><?php echo "Php " . number_format($cart['price'] * $cart['quantity'], 2); ?></td>
                    <td><a href="?delete_from_cart=<?php echo $cart['order_id']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                    </tr>
                    <?php }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<nav class='navbar navbar-light bg-light'>";
                    echo "<form class='form-inline mr-auto'>";
                    echo "<input class='form-check-input' type='checkbox' id='checkAll'>";
                    echo "<label class='form-check-label' for='checkAll'>Check All</label>";
                    echo "</form>";
                    echo "<span id='totalPrice' class='total'></span>"; // Added class to style the total price
                    echo "<button type='submit' class='btn btn-warning' id='checkoutBtn'>Checkout</button>"; 
                    echo "</nav>";
                    echo "</form>";                    
                } else {
                    echo "<p class='text-center'>Your cart is empty or all items are out of stock.</p>";
                }
                ?>
                <?php
                if (isset($_GET['msg'])) {
                    $msg = $_GET['msg'];
                    $status = ($msg == 1) ? "Order Placed Successfully." : $msg;
                    $alert_class = ($msg == 1) ? "alert-success" : "alert-danger";
                    echo "<div class='alert $alert_class'>$status</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script>
    // Function to calculate total price
    function calculateTotalPrice() {
        var checkboxes = document.getElementsByName("selected_items[]");
        var totalPrice = 0;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                var row = checkboxes[i].closest("tr");
                var price = parseFloat(row.cells[4].innerText.replace("Php ", "").replace(",", ""));
                totalPrice += price;
            }
        }
        return totalPrice.toFixed(2);
    }

    // Update total price when checkboxes are changed
    function updateTotalPrice() {
        document.getElementById('totalPrice').innerText = "Total Price: Php " + calculateTotalPrice();
    }

    var checkboxes = document.getElementsByName("selected_items[]");
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotalPrice);
    });

    // Update total price when "Check All" checkbox is clicked
    document.getElementById("checkAll").addEventListener("change", function () {
        var checkboxes = document.getElementsByName("selected_items[]");
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
        updateTotalPrice(); // Update total price after checking/unchecking all checkboxes
    });

    // Update total price initially
    updateTotalPrice();

    // Handle checkout button click
    document.getElementById('checkoutBtn').addEventListener('click', function() {
        var totalPrice = calculateTotalPrice();
        if (totalPrice <= 0) {
            alert('Please select at least one item to checkout.');
        } else {
            // Proceed with checkout
            window.location.href = 'checkout.php?totalPrice=' + totalPrice;
        }
    });
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

