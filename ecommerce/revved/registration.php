<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #212529;
            font-family: 'Roboto', sans-serif;
            color: #ddd;
        }
        h3 {
            text-transform: uppercase; /* Convert text to uppercase */
            font-family: 'Montserrat', sans-serif; /* Applies the Montserrat font */
            color: #007bff;
        }

        label {
            color: #ddd;
        }
        .form-control {
            background-color: #fff; /* Dark Grey */
            color: #000;
            border-color: #343a40; /* Dark Grey */
        }
        .btn-success {
            background-color: #007bff; /* Blue */
            border-color: #007bff; /* Blue */
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #0056b3; /* Darker Blue */
            border-color: #004d9b; /* Darker Blue */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
            background-color: #343a40; /* Dark Grey */
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="display-4 text-center mb-4">User Registration</h3>
        <?php
            if(isset($_GET['error'])){
                echo "<div class='alert alert-danger' role='alert'>" . $_GET['error'] . "</div>";
            }
        ?>
        <form action="process_registration.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="r_fullname">Full Name</label>
                <input name="r_fullname" id="r_fullname" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="r_username">Username</label>
                <input name="r_username" id="r_username" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="r_password">Password</label>
                <input name="r_password" id="r_password" type="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="r_conf_password">Confirm Password</label>
                <input name="r_conf_password" id="r_conf_password" type="password" class="form-control">
            </div>
            <div class="form-group">
                <label for="r_address">Address</label>
                <input name="r_address" id="r_address" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="r_user_img">Upload Image</label>
                <input name="r_user_img" id="r_user_img" type="file" class="form-control">
            </div>
            <input type="hidden" name="r_user_cat" value="U">
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </div>
        </form>
    </div>
</body>
<script src="js/bootstrap.js"></script>
</html>
