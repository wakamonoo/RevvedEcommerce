<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=not_logged_in");
    exit; // Stop script execution
}

include_once "../db.php";

$user_id = $_SESSION['user_id'];
$sql_fetch_user = "SELECT user_id, uname, fname, address, user_cat, user_img, password FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql_fetch_user);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: index.php?error=user_not_found");
    exit; // Stop script execution
}

$passwordError = "";
$showForm = false; // Initialize $showForm

// Password verification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];

    if ($password === $user['password']) {
        // Password is correct, show the update form
        $showForm = true;
    } else {
        // Password is incorrect, display error message
        $passwordError = "Incorrect password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #343a40; /* Dark grey */
            color: #f8f9fa; /* Light grey */
        }
        .card {
            margin-top: 50px;
            background-color: #212529; /* Darker grey */
            color: #f8f9fa; /* Light grey */
            border: 1px solid #dc3545; /* Red */
        }
        .card-header {
            background-color: #dc3545; /* Red */
            color: #f8f9fa; /* Light grey */
        }
        .form-group label {
            color: #f8f9fa; /* Light grey */
        }
        .form-control {
            background-color: #212529; /* Darker grey */
            border: 1px solid #dc3545; /* Red */
            color: #f8f9fa; /* Light grey */
        }
        .form-control:focus {
            background-color: #343a40; /* Dark grey */
            border-color: #dc3545; /* Red */
            color: #f8f9fa; /* Light grey */
        }
        .btn-primary {
            background-color: #dc3545; /* Red */
            border-color: #dc3545; /* Red */
        }
        .btn-primary:hover {
            background-color: #c82333; /* Darker red */
            border-color: #c82333; /* Darker red */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="display-4">Update Information</h3>
                </div>
                <div class="card-body">
                    <?php if ($passwordError): ?>
                        <!-- Display error message -->
                        <div class="alert alert-danger" role="alert">
                            <?php echo $passwordError; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($showForm): ?>
                        <!-- Display update form -->
                        <form action="update_process.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="uname">Username</label>
                                <input type="text" class="form-control" id="uname" name="uname" value="<?php echo $user['uname']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="fname">Fullname</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $user['fname']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="user_img">Profile Picture</label>
                                <input type="file" class="form-control-file" id="user_img" name="user_img">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    <?php else: ?>
                        <!-- Password verification form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="password">Current Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Verify Password</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

