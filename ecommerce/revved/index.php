<?php
include "db.php";

$is_logged_in = isset($_SESSION['username']);

// Check if search query is set
if(isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    // Query to retrieve items with search filter
    $get_result = mysqli_query($conn, "SELECT items.*, IFNULL(AVG(reviews.rating), 0) AS avg_rating
                                        FROM items
                                        LEFT JOIN reviews ON items.item_id = reviews.item_id
                                        WHERE items.stocks > 0 AND items.item LIKE '%$search%'
                                        GROUP BY items.item_id
                                        ORDER BY items.item_id DESC");
} else {
    // Query to retrieve all items if no search query is provided
    $get_result = mysqli_query($conn, "SELECT items.*, IFNULL(AVG(reviews.rating), 0) AS avg_rating
                                        FROM items
                                        LEFT JOIN reviews ON items.item_id = reviews.item_id
                                        WHERE items.stocks > 0
                                        GROUP BY items.item_id
                                        ORDER BY items.item_id DESC");
}

if (!$get_result) {
    // Handle the case where the query fails, maybe log the error or display a message to the user
    echo "Error: " . mysqli_error($conn);
    exit;
}
$lowest_price_items_query = mysqli_query($conn, "SELECT * FROM items WHERE stocks >= 0 ORDER BY price ASC LIMIT 2");

if (!$lowest_price_items_query) {
    // Handle the case where the query fails
    echo "Error: " . mysqli_error($conn);
    exit;
}
$sql = "SELECT items.*, AVG(reviews.rating) AS avg_rating 
        FROM items 
        LEFT JOIN reviews ON items.item_id = reviews.item_id 
        WHERE items.stocks > 0 
        GROUP BY items.item_id 
        HAVING AVG(reviews.rating) = 5
        LIMIT 5";
$buyers_choice_result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Main Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="css/bootstrap.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
<style>
body {
    background-color: #343a40;
    color: #ffffff;
    padding-top: 6px;
    font-family: Arial, sans-serif;
}

.btn {
    display: inline-block;
    font-weight: bold;
    text-transform: uppercase; 
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: none; 
    padding: 0.375rem 0.75rem;
    font-size: 1.2rem; 
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
.carousel img {
    width: 100%;
    height: 500px; /* Ensure the images fill the carousel height */
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

.badge {
    color: blue;
    padding: 0; /* Remove padding */
    border-radius: 50%; /* Make it circular */
    position: relative; /* Position relative for absolute positioning */
    top: 12px; /* Adjust vertical positioning */
    left: -14px; /* Adjust horizontal positioning */
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
/* Style for deactivated items */
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

.stars .star {
    color:#f39c12; /* Gold color for stars */
    font-size: 20px; /* Adjust star size */
    margin: 0 1px;
}

.stars .star.filled {
    color: #f39c12;/* Gold color for filled stars */
}
.toolbar{
    background-color: #dc3545;
    width: 100%;
    margin-top: -10px;
    height: 80px;
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
.square-container {
    position: absolute;
    left: 30px;
    top: 115px;
    display: flex;
    justify-content: center;
    align-items: center; /* Center items vertically */
    flex-wrap: wrap;
    margin-left: auto;
    margin-right: auto;
    max-width: 35%; /* Adjust maximum width as needed */
    max-height: 198px;
    background-color: #212529;
    border-radius: 10px;
}

.col-md-6 {
    flex: 0 0 45%; /* Each item takes 50% of the container width */
    margin-bottom: 5px;
    margin-left: 15px;
    margin-top: 20px;
}
.card {
    height: 55%;
}

.card-img-top {
    height: 100px; /* Adjust height as needed */
}
.card-body {
    height: 200px; /* Adjust height as needed */
    padding: 10px; /* Add padding to provide space around the text */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center; /* Center items horizontally */
}

.card-title {
    font-size: .8rem; /* Adjust font size for the title */
    margin-bottom: 5px; /* Add margin to separate the title from other content */
    text-align: center; /* Center the text horizontally */
    font-weight: bold; /* Optionally make the price text bold */
}

.card-text {
    font-size: 14px; /* Adjust font size for the text */
    line-height: 1.4; /* Adjust line height for better readability */
    margin-bottom: 10px; /* Add margin to separate the text from other content */
}

.p2 {
    font-size: .8rem; /* Adjust font size for the price */
    margin-top: auto; /* Align price to the bottom */
}
.low {
    z-index: 999;
    position: absolute;
    display: flex;
    justify-content: center;
    left: 20px;
    top: 100px;
    text-transform: uppercase; 
    font-family: 'Montserrat', sans-serif;
    color: black;
    text-shadow: 
        -1px -1px 0 #fff,  
         1px -1px 0 #fff,
        -1px 1px 0 #fff,
         1px 1px 0 #fff; /* White outline */
    transform: rotate(-3deg); /* Tilt the text clockwise by 3 degrees */
}
/* Category section styles */
.category-section {
    background-color: #212529;
    padding: 50px; /* Adjusted padding */
    border-radius: 10px;
    margin-top: 50px;
    max-width: 100%; /* Ensures the container fills the width of the page */
    margin: 0 auto; /* Centers the container horizontally */
    width: 100%; /* Make sure it occupies the full width */
    display: flex;
    justify-content: center; /* Center the container horizontally */
}


.category-row {
    display: flex;
    justify-content: center;
}

.category-col {
    width: 100%;
    max-width: 2500px;
}

.category-title {
    text-transform: uppercase;
    font-size: 24px;
    font-family: sans-serif;
    color: white;
    text-align: left;
    margin-bottom: 20px;
}

.category-card-container {
    display: flex;
    justify-content: center; /* Center the category cards */
    gap: 0; /* Removed gap */
    flex-wrap: wrap; /* Allows wrapping if there are many categories */
}


.category-card-col {
    flex: 1 0 19%; /* Adjusted width to fit 5 cards in a row, considering margins */
    max-width: 19%; /* Adjusted width to fit 5 cards in a row */
    margin: 0.5%; /* Slight margin for spacing */
}

.category-card {
    background-color: #212529;
    border-radius: 10px;
    overflow: hidden;
    position: relative; /* Ensure the title is positioned relative to the card */
    text-align: center;
    transition: transform 0.3s ease;
    box-sizing: border-box; /* Ensure padding and borders are included in the element's total width and height */
}
.category-card:hover {
    transform: translateY(-20px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.category-card-title {
    position: absolute;
    bottom: 10px; /* Position 10px from the bottom of the card */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Adjust position to center */
    font-size: 18px;
    font-weight: bold;
    color: #ffffff; /* Ensure text is readable */
    padding: 5px 10px; /* Optional: add padding */
    border-radius: 5px; /* Optional: add border radius */
}

.category-card img {
    height: 400px;
    width: 100%; /* Adjusted to make the image fit the card width */
    object-fit: cover; /* Ensure the image covers the whole area */
    display: block;
}
.all-items {
    /* Add your styles here */
    font-size: 24px;
    font-weight: bold;
    margin-left: 30px;
    margin-top: 30px;
    text-transform: uppercase;
}
.categ {
    /* Add your styles here */
    font-size: 24px;
    font-weight: bold;
    margin-left: 30px;
    margin-top: 30px;
    margin-bottom: 30px;
    text-transform: uppercase;
}
/* Footer Styles */

.footer {
    background-color: #212529;
}

/* Title color */
.footer h5 {
    color: #dcdcdc; /* Light gray for titles */
}
/* Text color */
.footer p,
.footer ul li a,
.team-member h6,
.social-links a {
    color: #bbb; /* Grayish text color */
}

.footer ul {
    padding-left: 0;
    list-style: none;
}

.footer ul li {
    margin-bottom: 10px;
}

.footer ul li a {
    text-decoration: none;
}

.footer ul li a:hover {
    color: #ccc; /* Light gray text color on hover */
}

.team-member {
    margin-bottom: 20px;
}

.team-member h6 {
    margin-bottom: 5px;
}

.social-links a {
    margin-right: 5px;
}

.social-links a:hover {
    color: #ccc; /* Light gray text color on hover */
}

.sponsor-container {
    display: flex;
}

.spon {
    max-width: 100px; /* Adjust as needed */
    max-height: 50px; /* Adjust as needed */
    margin-right: 10px; /* Remove any margins */
    padding: 0; /* Remove any paddings */
}

.footer {
    position: relative; /* Position the footer fixed at the bottom */
    left: 0; /* Position from the left edge */
    bottom: 0; /* Position from the bottom edge */
    width: 100%; /* Make the footer full width */
    background-color: #212529;
    padding: 20px; /* Add padding to the footer */
    color: #ffffff;
    z-index: 1000; /* Ensure the footer is above other content */
    top: 500px;
}


.footer::after {
    content: "";
    background-image: url('img/tire.png'); /* Replace 'path/to/tire-image.jpg' with the actual path to your tire image */
    background-size: contain; /* Ensure the background image fits inside the footer */
    background-position: right; /* Position the background image to the right */
    background-repeat: no-repeat; /* Prevent the background image from repeating */
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 40%; /* Adjust the width of the tire image */
    z-index: 0; /* Ensure the background image is behind the content */
}

.footer-content {
    position: relative; /* Ensure that the footer content is positioned relative */
    z-index: 1; /* Ensure that the footer content is above the background image */
}
.list{
    color: #bbb;
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
.promo-container {
    margin-top: 100px;
    margin-bottom: 100px;
    width: 100%;
    height: 500px;
    background-color: #212529;
    color: white;
    position: relative;
    padding: 20px;
    box-sizing: border-box;
    border-radius: 1px;
}
.item1-image {
    position: absolute;
    top: -30px;
    right: 10px;
    height: 120%;
    width: 60%;
    transform: translateX(0px);
    animation: float 6s ease-out infinite;
}

@keyframes float {
    0%{
        transform: translateY(0px);
    }
    50%{
        transform: translateY(-60px);
    }
    100%{
        transform: translateY(0px);
    }
}

.promo-text {
    text-align: center;
    margin-top: 140px;
    color: #fff;
}

.promo-text h1 {
    position: absolute;
    margin-left: 60px;
    margin-top: 30px;
    font-size: 80px;
    font-family: 'Montserrat', sans-serif;
    text-transform: uppercase;
}
.promo-text h2 {
    position: absolute;
    margin-left: 60px;
    margin-top: 110px;
    font-size: 20px;
    text-transform: uppercase;
}
.promo-text h3 {
    position: absolute;
    margin-left: 60px;
    margin-top: -40px;
    font-size: 80px;
    font-family: 'Montserrat', sans-serif;
    text-transform: uppercase;
}
.website-link {
    margin-left: -580px;
    position: absolute;
    bottom: 20px;
    width: 100%;
    text-align: center;
    color: white;
    text-decoration: none;
    font-size: 16px;
}

.website-link i {
    margin-right: 5px;
}
</style>
</head>
<body>
    <div class="d-flex justify-content-end mb-3">
        <div class="toolbar">
            <a href="index.php" class="home">
                <img src="img/logo.png" alt="Home" style="width: 150px; height: 60px;">
            </a>
            <h3 class="tag">Shop and Rev Up <span class="username">GUEST</span></h3>
            <div class="search-container">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Search...">
                    <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                </form>
            </div>
            <!-- Cart icon with count -->
            <a href="index.php" class="cart" id="cartLink">
                <img src="img/cart.png" alt="Cart Icon" style="width: 16px; height: 16px;">
            </a>
            <!-- Display cart count -->
            <a href="login.php" class="login">LOGIN</a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h2 class="low">best deals</h2>
                <div class="square-container">
                    <div class="row">
                        <?php 
                        $counter = 0; 
                        while ($row = mysqli_fetch_assoc($lowest_price_items_query)) { 
                            if ($counter < 2) {
                                echo '<div class="col-md-6">';
                            } else {
                                echo '<div class="col-md-6 mt-4">';
                            }
                        ?>
                        <div class="card">
                            <img src="<?php echo $row['item_img']; ?>" class="card-img-top" alt="Item Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['item']); ?></h5>
                                <p class="p2">Price: ₱<?php echo $row['price']; ?></p>
                                <!-- Add to Cart button or any other action -->
                                <form action="index.php" method="get" class="input-group">
                                    <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                                </form>
                            </div>
                        </div>
                        </div>
                        <?php 
                            $counter++;
                        } 
                        ?>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Carousel -->
                    <div class="carousel-container">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="2000">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="img/1.jpg" class="d-block w-100" alt="..." style="height: 200px;">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/2.jpg" class="d-block w-100" alt="..." style="height: 200px;">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/3.jpg" class="d-block w-100" alt="..." style="height: 200px;">
                                </div>
                            </div>
                            <ol class="carousel-indicators">
                                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="categ">Shop by Category</div>
                <div class="container category-section">
                    <div class="category-row">
                        <div class="category-col">
                            <div class="category-card-container">
                                <?php 
                                // Retrieve categories from the database
                                $categories_query = mysqli_query($conn, "SELECT DISTINCT category FROM items");
                                while ($category_row = mysqli_fetch_assoc($categories_query)) { 
                                    $category_name = htmlspecialchars($category_row['category']);
                                    
                                    // Define the image path for each category (replace this with database retrieval if needed)
                                    $category_images = [
                                        'Lights and Electrical' => 'img/lights_electrical.jpg',
                                        'Performance Parts' => 'img/Performance Parts.png',
                                        'Accessories and Add-ons' => 'img/Accessories and Add-ons.png',
                                        'Frame and Body Parts' => 'img/Frame and Body Parts.jpg',
                                        'Engine and Internal Parts' => 'img/Engine and Internal Parts.jpg',
                                        // Add more categories and their corresponding images here
                                    ];

                                    $category_image = isset($category_images[$category_name]) ? $category_images[$category_name] : '/img/default_category.jpg'; // Default image if category not found
                                ?>
                                <div class="category-card-col">
                                    <div class="category-card">
                                        <img src="<?php echo $category_image; ?>" alt="<?php echo $category_name; ?>">
                                        <h5 class="category-card-title"><?php echo $category_name; ?></h5>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="all-items">TOP RATED ITEMS</div>
                <div class="item-container <?php echo mysqli_num_rows($buyers_choice_result) > 0 ? 'single-item' : ''; ?>">
                    <?php while ($row = mysqli_fetch_assoc($buyers_choice_result)) { ?>
                    <div class="item <?php echo $row['item_status'] === 'D' ? 'deactivated' : ''; ?>">
                    <img src="<?php echo $row['item_img']; ?>" alt="Item Image" class="photo">
                        <p class="text"><?php echo htmlspecialchars($row['item']); ?></p>
                        <div class="stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $row['avg_rating']) { // Change $row['rating'] to $row['avg_rating']
                                    echo '<i class="fas fa-star star filled"></i>';
                                } else {
                                    echo '<i class="far fa-star star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <p class="price">Price: ₱<?php echo $row['price']; ?></p>
                        <?php if ($row['item_status'] !== 'D') { ?>
                            <form action="index.php" method="get" class="input-group">
                                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                                <input type="number" class="form-control" name="cart_qty" min="1" max="<?php echo $row['stocks']; ?>">
                                <input type="submit" value="Add to Cart" class="btn btn-primary addToCartBtn">
                            </form>
                        <?php } ?>
                        <p class="stock-info">Stocks: <?php echo $row['stocks']; ?></p>
                        <a href="common_user/disp_rev.php?item_id=<?php echo $row['item_id']; ?>" class="review">Check Reviews</a>
                    </div>
                    <?php } ?>
                </div>
                <div class="promo-container">
                    <img src="img/airoh.png" alt="Item Image" class="item1-image">
                    <div class="promo-text">
                        <h1>AVIATOR 2.3</h1>
                        <h2>NOW AT ₱3,000.00</h2>
                        <h3>AIROH</h3>
                    </div>
                    <a href="index.php" class="website-link">
                        <i class="fas fa-globe"></i> www.revved.com
                    </a>
                </div>
                <div class="all-items">ALL ITEMS</div>
                <div class="item-container <?php echo mysqli_num_rows($get_result) > 0 ? 'single-item' : ''; ?>">
                    <?php while ($row = mysqli_fetch_assoc($get_result)) { ?>
                    <div class="item <?php echo $row['item_status'] === 'D' ? 'deactivated' : ''; ?>">
                    <img src="<?php echo $row['item_img']; ?>" alt="Item Image" class="photo">
                        <p class="text"><?php echo htmlspecialchars($row['item']); ?></p>
                        <div class="stars">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $row['avg_rating']) { // Change $row['rating'] to $row['avg_rating']
                                    echo '<i class="fas fa-star star filled"></i>';
                                } else {
                                    echo '<i class="far fa-star star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <p class="price">Price: ₱<?php echo $row['price']; ?></p>
                        <?php if ($row['item_status'] !== 'D') { ?>
                            <form action="index.php" method="get" class="input-group">
                                <input type="hidden" name="item_id" value="<?php echo $row['item_id']; ?>">
                                <input type="number" class="form-control" name="cart_qty" min="1" max="<?php echo $row['stocks']; ?>">
                                <input type="submit" value="Add to Cart" class="btn btn-primary addToCartBtn">
                            </form>
                        <?php } ?>
                        <p class="stock-info">Stocks: <?php echo $row['stocks']; ?></p>
                        <a href="common_user/disp_rev.php?item_id=<?php echo $row['item_id']; ?>" class="review">Check Reviews</a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function () {
            // Attach click event to category cards
            $('.category-card').click(function () {
                // Get the category name
                var categoryName = $(this).find('.category-card-title').text().trim();

                // Redirect to a page where items belonging to the selected category are displayed
                window.location.href = 'category.php?category=' + encodeURIComponent(categoryName);
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            // Check if the user is logged in (this variable is set in the PHP script)
            var isLoggedIn = <?php echo json_encode($is_logged_in); ?>;

            // Attach event listener to all Add to Cart buttons
            document.querySelectorAll(".addToCartBtn").forEach(function (button) {
                button.addEventListener("click", function (event) {
                    if (!isLoggedIn) {
                        // Display error message
                        alert("Please login or sign up first.");
                        event.preventDefault(); // Prevent the default behavior of the form submission
                    }
                });
            });

            // Attach event listener to the cart link
            document.getElementById("cartLink").addEventListener("click", function (event) {
                if (!isLoggedIn) {
                    // Display error message
                    alert("Please login or sign up first.");
                    event.preventDefault(); // Prevent the default behavior of the link
                }
            });
        });
    </script>
</body>
<footer class="footer mt-auto py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="img/logo.png" alt="Company Logo" class="img-fluid mb-3">
                <p class="mb-4">Unleash the Beast - Performance Parts for the Ultimate Ride!</p>
                <h5>About Us</h5>
                <div class="d-flex justify-content-between">
                    <div class="team-member">
                        <h6>Joven Bataller</h6>
                        <p>Co-Founder</p>
                        <div class="social-links">
                            <a href="https://web.facebook.com/joven.serdanbataller"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/wakamonoooo/"><i class="fab fa-instagram"></i></a>
                            <a href="mailto:joven.serdanbataller21@gmail.com"><i class="far fa-envelope"></i></a>
                        </div>
                    </div>
                    <div class="team-member">
                        <h6>Jhonmel Christian Bobis</h6>
                        <p>Co-Founder</p>
                        <div class="social-links">
                            <a href="https://web.facebook.com/jhonmelchristian.bobis"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="mailto:jane@example.com"><i class="far fa-envelope"></i></a>
                        </div>
                    </div>
                    <div class="team-member">
                        <h6>Harley Gepila</h6>
                        <p>Co-Founder</p>
                        <div class="social-links">
                            <a href="https://web.facebook.com/harley.gepila"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/si_harle"><i class="fab fa-instagram"></i></a>
                            <a href="mailto:gepilaharley@gmail.com"><i class="far fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Office Locations</h5>
                <ul class="list">
                    <li>Libon, Albay</a></li>
                    <li>California St., Apud</a></li><br>
                    <li>Polangui, Albay</a></li>
                    <li>Sugcad</a></li><br>
                    <li>Oas, Albay</a></li>
                    <li>Balogo</a></li>
                </ul>
                <div class="sponsor-container">
                    <img src="img/flash.jpg" alt="Flash Logo" class="spon">
                    <img src="img/gcash.png" alt="GCash Logo" class="spon">
                    <img src="img/jnt.jpg" alt="J&T Logo" class="spon">
                </div>
            </div>
        </div>
    </div>
</footer>
</html>

