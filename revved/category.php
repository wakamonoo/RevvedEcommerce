<?php
include "db.php";

if (!isset($_GET['category'])) {
    echo "No category selected.";
    exit;
}

$category = mysqli_real_escape_string($conn, $_GET['category']);

// Query to retrieve items for the selected category
$get_result = mysqli_query($conn, "SELECT items.*, IFNULL(AVG(reviews.rating), 0) AS avg_rating
                                    FROM items
                                    LEFT JOIN reviews ON items.item_id = reviews.item_id
                                    WHERE items.stocks >= 0 AND items.category = '$category'
                                    GROUP BY items.item_id
                                    ORDER BY items.item_id DESC");

if (!$get_result) {
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
<link rel="stylesheet" href="css/bootstrap.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
<style>
/* Include your styles here */
body {
    background-color: #343a40;
    color: #ffffff;
    font-family: Arial, sans-serif;
    padding-top: 20px;
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
    color: #dc3545;
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

.stars .star {
    color: #ffdd00; /* Gold color for stars */
    font-size: 20px; /* Adjust star size */
    margin: 0 1px;
}

.stars .star.filled {
    color: #ffdd00; /* Gold color for filled stars */
}
</style>
</head>
<body>
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
                <a href="review.php?item_id=<?php echo $row['item_id']; ?>" class="review">Check Reviews</a>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
