<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login or Create Account</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #212529; /* Dark gray background */
            color: #ffffff; /* White text */
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-form {
            max-width: 500px; /* Increased the max-width */
            padding: 30px; /* Increased padding for better spacing */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #343a40;
            color: #fff; /* Dark text color */
        }
        .login-title {
            color: #fff;
            text-align: center; /* Center align the title */
            margin-bottom: 30px; /* Adjust margin */
            text-transform: uppercase; /* Convert text to uppercase */
            font-family: 'Montserrat', sans-serif; /* Applies a different font to the username */
        }
        .input-group {
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            background-color: #007bff; /* Blue button */
            border-color: #007bff;
        }
        .btn-login:hover {
            background-color: #0056b3; /* Dark blue hover */
            border-color: #0056b3;
        }
        .text-center a {
            color: #007bff; /* Blue link color */
        }
        .text-center a:hover {
            color: #0056b3; /* Dark blue hover */
            text-decoration: none; /* Remove underline on hover */
        }
        .logo {
            width: 400px; /* Adjust the width as needed */
            height: auto; /* Maintain aspect ratio */
        }

    </style>
</head>
<body>
   
   <div class="login-container">
       <div class="login-form">
       <img src="img/logo.png" alt="revved logo" class="logo">
           <h2 class="login-title">Login</h2>
           <form action="process_login.php" method="POST">
               <div class="input-group">
                   <input name="f_username" type="text" class="form-control" placeholder="Username">
               </div>
               <div class="input-group">
                   <input name="f_password" type="password" class="form-control" placeholder="Password">
               </div>
               <div class="input-group">
                   <input type="submit" value="Login" class="btn btn-primary btn-login">
               </div>
           </form>
           <hr>
           <p class="text-center">Don't have an account? <a href="registration.php">Create Account</a></p>
       </div>
   </div>
    
</body>
<script src="js/bootstrap.js"></script>
</html>
