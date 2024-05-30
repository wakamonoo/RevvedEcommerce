
<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not_logged_in");
    exit; // Stop script execution
}

include_once "../db.php";

// Fetch the logged-in user's information
$user_id = $_SESSION['user_id'];
$sql_fetch_user = "SELECT user_id, uname, fname, address, user_cat, user_img FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql_fetch_user);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php?error=user_not_found");
    exit; // Stop script execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #212529; /* Light grey */
            color: #fff; /* Dark grey */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .user-info {
            width: 80%;
            max-width: 400px; /* Adjusted maximum width for ID card appearance */
            padding: 30px;
            background-color: #343a40; /* White */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Added shadow effect */
        }
        .user-img-container {
            text-align: center; /* Center align the image */
            margin-bottom: 20px;
        }
        .user-img {
            width: 150px; /* Reduced image size */
            height: 150px; /* Reduced image size */
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #dc3545; /* Red */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Added shadow effect */
        }
        .user-details {
            text-align: left; /* Left align the details */
        }
        .card-footer {
            text-align: center; /* Center align the footer */
            padding-top: 20px; /* Added padding to separate footer from details */
        }
        .btn-primary {
            color: white;
            background-color: #dc3545; /* Red */
            border-color: #dc3545; /* Red */
            padding: 10px 20px; /* Adjusted padding for button */
            font-size: 1rem; /* Adjusted font size */
            text-decoration: none; /* Remove default link underline */
            border-radius: 5px; /* Added button border radius */
            transition: all 0.3s ease; /* Added transition for smooth hover effect */
        }
        .btn-primary:hover {
            background-color: #c82333; /* Darker red */
            border-color: #c82333; /* Darker red */
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="user-info">
        <div class="user-img-container">
            <img src="../uploads/<?php echo $user['user_img']; ?>" class="img-thumbnail user-img" alt="User Image">
        </div>
        <div class="user-details">
            <p><strong>Username:</strong> <?php echo $user['uname']; ?></p>
            <p><strong>Full Name:</strong> <?php echo $user['fname']; ?></p>
            <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
        </div>
        <div class="card-footer">
            <a href="index.php" class="btn btn-primary">Back to Home</a>
            <a href="update.php" class="btn btn-primary">Update Information</a>
        </div>
    </div>
    <script src="js/bootstrap.js"></script>
</body>
</html>
