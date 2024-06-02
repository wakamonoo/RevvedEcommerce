<?php
include_once "../db.php";
session_start();

if ($_SESSION['user_cat'] != 'U') {
    header("location: ../index.php");
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['order_ref_number'])) {
    $order_ref_number = $_GET['order_ref_number'];
} else {
    header("location: ../index.php");
    exit;
}

$sql_order = "SELECT `item_id` FROM `order` WHERE `order_ref_number` = '$order_ref_number' AND `user_id` = '$user_id'";
$result_order = mysqli_query($conn, $sql_order);

$item_ids = [];
if (mysqli_num_rows($result_order) > 0) {
    while ($row_order = mysqli_fetch_assoc($result_order)) {
        $item_ids[] = $row_order['item_id'];
    }
} else {
    echo '<script>alert("Order not found."); window.location.href = "orders.php";</script>';
    exit;
}

if (isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    $created_at = date('Y-m-d H:i:s');

    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["review_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["review_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo '<script>alert("File is not an image.");</script>';
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo '<script>alert("Sorry, file already exists.");</script>';
        $uploadOk = 0;
    }

    if ($_FILES["review_image"]["size"] > 500000) {
        echo '<script>alert("Sorry, your file is too large.");</script>';
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");</script>';
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo '<script>alert("Sorry, your file was not uploaded.");</script>';
    } else {
        if (move_uploaded_file($_FILES["review_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;

            foreach ($item_ids as $item_id) {
                $insert_query = "INSERT INTO reviews (user_id, item_id, rating, review_text, created_at, order_ref_number, rev_img) 
                                VALUES ('$user_id', '$item_id', '$rating', '$review', '$created_at', '$order_ref_number', '$image_path')";
                $insert_result = mysqli_query($conn, $insert_query);

                if (!$insert_result) {
                    echo '<script>alert("Failed to submit review for item ID ' . $item_id . '. Please try again.");</script>';
                }
            }

            echo '<script>alert("Review submitted successfully!"); window.location.href = "tracking.php";</script>';
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #212529;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #343a40;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            padding: 30px;
        }
        h1 {
            color: #fff;
            margin-bottom: 30px;
        }
        .form-group label {
            font-weight: bold;
            color: #fff;
        }
        .btn-primary {
            background-color: #dc3545;
            border: none;
        }
        .btn-primary:hover {
            background-color: #c82333;
        }
        .star {
            font-size: 2rem;
            color: #e4e5e9;
            cursor: pointer;
            transition: color 0.3s;
        }
        .star.selected {
            color: #f39c12;
        }

    </style>
</head>
<body>
<div class="container">
    <h1>Add Review</h1>
    <form action="review.php?order_ref_number=<?php echo $order_ref_number; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="rating">Rating:</label><br>
            <div class="star-rating">
                <span class="star" data-rating="1">&#9733;</span>
                <span class="star" data-rating="2">&#9733;</span>
                <span class="star" data-rating="3">&#9733;</span>
                <span class="star" data-rating="4">&#9733;</span>
                <span class="star" data-rating="5">&#9733;</span>
                <input type="hidden" name="rating" class="rating-value" value="0">
            </div>
        </div>
        <div class="form-group">
            <label for="review">Review:</label>
            <textarea name="review" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="review_image">Upload Image:</label>
            <input type="file" name="review_image" class="form-control-file" accept="image/*" required>
        </div>
        <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        const ratingValue = document.querySelector('.rating-value');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(star.getAttribute('data-rating'));
                ratingValue.value = rating;
                
                stars.forEach(s => s.classList.remove('selected'));

                for (let i = 0; i <= rating - 1; i++) {
                    stars[i].classList.add('selected');
                }
            });
        });
    });
</script>

</body>
</html>
