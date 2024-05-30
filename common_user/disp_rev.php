<?php
include_once "../db.php";
session_start();

if ($_SESSION['user_cat'] != 'U') {
    header("location: ../index.php");
}

$user_id = $_SESSION['user_id'];

// Retrieve the item ID from the URL parameter
if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
} else {
    // Handle the case when the item ID is not provided
    // Redirect or display an error message
}

// Fetch reviews for the specified item
$sql_reviews = "SELECT r.rating, r.review_text, r.created_at, r.order_ref_number, r.rev_img, u.uname
                FROM reviews r
                JOIN users u ON r.user_id = u.user_id
                WHERE r.item_id = '$item_id'";

$result_reviews = mysqli_query($conn, $sql_reviews);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Reviews</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
            padding-top: 20px;
        }

        .star-rating {
            unicode-bidi: bidi-override;
            font-size: 20px;
            display: inline-block;
            color: #ffdd00; /* Default color for filled stars */
        }

        .star-rating > span {
            display: inline-block;
            position: relative;
            width: 1em;
            color: #ddd; /* Empty star color */
        }

        /* Change color of filled stars */
        .star-rating > span.filled {
            color: #ffdd00; /* Filled star color */
        }

        .review-image {
            max-width: 50%;
            max-height: 50vh; /* Adjust the maximum height as needed */
            width: auto;
            height: auto;
        }

        .card {
            background-color: #212529;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .card-title {
            color: #ffffff;
        }

        .card-subtitle {
            color: #ffffff;
        }

        .card-text {
            color: #ffffff;
        }

        .alert {
            background-color: #dc3545;
            color: #ffffff;
        }
        h1{
            text-transform: uppercase; /* Convert text to uppercase */
    font-family: 'Montserrat', sans-serif; /* Applies a different font to the username */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Item Reviews</h1>
    <?php if (mysqli_num_rows($result_reviews) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result_reviews)): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Rating:
                        <div class="star-rating">
                            <?php
                            $rating = $row['rating'];
                            // Loop to display the stars based on the rating
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<span class="filled">&#9733;</span>'; // Filled star
                                } else {
                                    echo '<span>&#9734;</span>'; // Empty star
                                }
                            }
                            ?>
                        </div>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">Reviewer: <?php echo $row['uname']; ?></h6>
                    <p class="card-text">Review: <?php echo $row['review_text']; ?></p>
                    <p class="card-text">Date Added: <?php echo $row['created_at']; ?></p>
                    <p class="card-text">Order Reference Number: <?php echo $row['order_ref_number']; ?></p>
                    <?php if (!empty($row['rev_img'])): ?>
                        <p class="card-text">Review Image:</p>
                        <img src="<?php echo $row['rev_img']; ?>" class="review-image" alt="Review Image">
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            No reviews to show.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
