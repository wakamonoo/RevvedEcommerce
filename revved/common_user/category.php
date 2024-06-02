<?php
include "../db.php";
session_start();
$s_user_id = $_SESSION['user_id'];
if($_SESSION['user_cat'] != 'U'){
    header("location: ../index.php");
}
if(isset($_GET['logout'])){
    session_destroy();
    header("location: ../index.php");
    die();
}
if (!isset($_GET['category'])) {
    echo "No category selected.";
    exit;
}

$category = mysqli_real_escape_string($conn, $_GET['category']);
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Construct the WHERE clause for the search query
$search_condition = $search_query ? "AND items.item LIKE '%$search_query%'" : '';

// Query to retrieve items for the selected category
$get_result = mysqli_query($conn, "SELECT items.*, IFNULL(AVG(reviews.rating), 0) AS avg_rating
                                    FROM items
                                    LEFT JOIN reviews ON items.item_id = reviews.item_id
                                    WHERE items.stocks > 0 AND items.category = '$category' $search_condition
                                    GROUP BY items.item_id
                                    ORDER BY items.item_id DESC");

if (!$get_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

// Fetch user information including user_img from the database
$user_query = mysqli_query($conn, "SELECT user_img FROM users WHERE user_id = '$s_user_id'");
if (!$user_query) {
    // Handle the case where the query fails
    echo "Error: " . mysqli_error($conn);
    exit;
}

$user_data = mysqli_fetch_assoc($user_query);
$user_img = $user_data['user_img'];

// Set user_img in session variable
$_SESSION['user_img'] = $user_img;


// Query to get the number of items in the cart for the logged-in user
$cart_count_result = mysqli_query($conn, "SELECT COUNT(*) AS cart_count FROM `order` WHERE status = 1 AND user_id = '$s_user_id'");
$cart_count_row = mysqli_fetch_assoc($cart_count_result);
$cart_count = $cart_count_row['cart_count'];

$lowest_price_items_query = mysqli_query($conn, "SELECT * FROM items WHERE stocks >= 0 ORDER BY price ASC LIMIT 2");

if (!$lowest_price_items_query) {
    // Handle the case where the query fails
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Category: <?php echo htmlspecialchars($category); ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="../css/bootstrap.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
<style>
/* Include your styles here */
body {
    background-color: #343a40;
    color: #ffffff;
    font-family: Arial, sans-serif;
}
.btn {
    display: inline-block;
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
}
.item-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); /* Adjusted to 270px */
    gap: 10px;
    padding: 10px;
    max-width: 100%; /* Ensures the container fills the width of the page */
    margin: 0 auto; /* Centers the container horizontally */
    width: 100%; /* Make sure it occupies the full width */
}

.item-container.single-item {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr)); /* Same setup for single-item class */
    gap: 10px;
    padding: 10px;
    max-width: 100%; /* Ensure the container fills the width of the page */
    margin: 0 auto; /* Center the container horizontally */
    width: 100%; /* Ensure it occupies the full width */
}

.item-container .item {
    width: 270px; /* Set individual item width */
    margin: 10px; /* Add some margin between items */
}

.item {
    text-align: center;
    background-color: #212529;
    padding-top: 100px; /* Increase the top padding to adjust the length */
    padding-bottom: 400px; /* Increase the bottom padding to adjust the length */
    padding-left: 110px; /* Keep the left padding unchanged */
    padding-right: 110px; /* Keep the right padding unchanged */
    border-radius: 10px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.item:hover {
    transform: scale(1.05); /* Increase size */
    transition: transform 0.3s ease; /* Smooth transition */
    box-shadow: 0 8px 16px rgba(0,0,0,0.2); /* Add shadow */
}

.item img {
    width: 200px; /* Set the width of the image */
    height: 200px; /* Maintain aspect ratio */
    cursor: pointer;
    margin-top: -80px; /* Adjust the top margin as needed */
}

.maximize-icon {
    position: absolute;
    top: 5px;
    right: 5px;
    color: white;
    cursor: pointer;
    z-index: 1; /* Ensure icon is above the image */
}

.item-container .form-control {
    min-width: 50px;
}

.item-container .btn-primary {
    padding: 0.25rem 0.5rem; /* Adjust padding to make the button smaller */
    font-size: 0.975rem; /* Optionally reduce font size */
}

.item-container .input-group {
    position: absolute;
    bottom: 30px; /* Adjust the distance from the bottom as needed */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    width: 80%; /* Adjust the width as needed */
}

.photo {
    position: absolute;
    top: 100px;
}

.text {
    position: absolute;
    top: 250px;
}

.price {
    position: absolute;
    top: 400px;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
}

.stock-info {
    position: absolute;
    font-size: 11px;
    bottom: -10px;
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    z-index: 1; /* Ensure icon is above the image */
}

.display-3 {
    text-transform: uppercase;
    font-size: 24px; /* Adjust the font size as needed */
    font-family: sans-serif;
    margin-left: -595px;
    margin-top: 150px;
}

.col {
    width: 100%; /* Ensure it takes full width */
    padding: 20px; /* Adjust padding as needed */
    box-sizing: border-box; /* Ensure padding does not affect the width */
}

.review {
    position: absolute;
    top: 350px;
    color: white;
}

.logo {
    margin-top: -60px;
    margin-left: 5px;
}

.carousel-container {
    display: flex;
    justify-content: flex-end; /* Align the carousel to the right */
    width: 100%;
}

.carousel {
    width: 60%;
    border-radius: 10px;
    overflow: hidden; /* Ensure images don't overflow */
}

.carousel-inner {
    border-radius: 10px;
}

.carousel-item img {
    width: 100%;
}

.carousel-indicators {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    width: fit-content;
}

.carousel-indicators li {
    display: inline-block;
    width: 10px; /* Initial width of the dots */
    height: 10px; /* Initial height of the dots */
    margin: 0 5px; /* Adjust the margin between dots */
    background-color: rgba(255, 255, 255, 0.5); /* Adjust the color and transparency of the dots */
    border-radius: 50%; /* Ensure the dots are circular */
    transition: all 0.3s ease; /* Smooth transition for size changes */
}

.carousel-indicators li.active {
    width: 12px;
    height: 12px; /* Height of the active dot */
    background-color: white; /* Color of the active dot */
}
.login {
    position: absolute;
    right: 100px; /* Align to the right of the page */
    top: 15px; /* Optional: Align to the top of the page */
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

.login:hover {
            color: #343a40 !important;; /* Change text color on hover */
        }


.btn-primary:hover {
    background-color: darkblue !important; /* Dark blue color */
    border-color: #007bff !important; /* Matching border color */
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
.item.deactivated {
    background-color: #dc3545;
}
.stars {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 90px;
    position: absolute; /* or 'fixed' depending on your needs */
    top: 0;
    left: 0;
    width: 100%; /* adjust as necessary */
    height: 100%; /* adjust as necessary */
}
.review {
    display: inline-block;
    margin-top: 10px;
    color: #3498db;
    text-decoration: none;
    transition: color 0.3s ease;
}

.review:hover {
    color: #2980b9;
}
.stars .star {
    color: #f39c12; /* Gold color for stars */
    font-size: 20px; /* Adjust star size */
    margin: 0 1px;
}

.stars .star.filled {
    color: #f39c12; /* Gold color for filled stars */
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
.item.deactivated {
    background-color: #dc3545;
    position: relative;
}

.item.deactivated::before {
    content: "UNAVAILABLE"; /* Text for the label */
    color: white; /* Text color for the label */
    padding: 4px 8px; /* Padding for the label */
    position: absolute; /* Position the label relative to the item */
    top: 30; /* Position at the top of the item */
    font-family: 'Montserrat', sans-serif; /* Specify Montserrat font */
    z-index: 1; /* Ensure the label is displayed on top */
    text-align: center; /* Align text to the center */
    width: calc(100% - 20px); /* Make label width equal to item width minus padding */
    border: 2px solid white;
    font-size: 30px;
    text-shadow: 
        -1px -1px 0 #000,  
        1px -1px 0 #000,
        -1px 1px 0 #000,
        1px 1px 0 #000; /* Font outline */
    box-shadow: 
        inset -1px -1px 0 #000,  
        inset 1px -1px 0 #000,
        inset -1px 1px 0 #000,
        inset 1px 1px 0 #000; /* Border outline */
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
    height: 80px;
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
            <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </form>
    </div>
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
    <h2 class="text-center mb-4">Category: <?php echo htmlspecialchars($category); ?></h2>
    <div class="item-container">
        <?php while ($row = mysqli_fetch_assoc($get_result)) { ?>
            <div class="item <?php echo $row['item_status'] === 'D' ? 'deactivated' : ''; ?>">
                <img src="<?php echo $row['item_img']; ?>" alt="Item Image" class="photo">
                <p class="text"><?php echo htmlspecialchars($row['item']); ?></p>
                <div class="stars">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $row['avg_rating']) {
                            echo '<i class="fas fa-star star filled"></i>';
                        } else {
                            echo '<i class="far fa-star star"></i>';
                        }
                    }
                    ?>
                </div>
                <p class="price">Price: ₱<?php echo $row['price']; ?></p>
                <?php if ($row['item_status'] !== 'D') { ?>
                    <form action="process_add_to_cart.php" method="get" class="input-group">
                        <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                        <input type="number" class="form-control" name="cart_qty" min="1" max="<?php echo $row['stocks']; ?>">
                        <input type="submit" value="Add to Cart" class="btn btn-primary">
                    </form>
                <?php } ?>
                <p class="stock-info">Stocks: <?php echo $row['stocks']; ?></p>
                <a href="disp_rev.php?item_id=<?php echo $row['item_id']; ?>" class="review">Check Reviews</a>
            </div>
        <?php } ?>
    </div>
</div>
<script>
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