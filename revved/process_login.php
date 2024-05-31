<?php
include_once "db.php";
session_start();

if(isset($_POST['f_username'])){
    $uname = $_POST['f_username'];
    $pword = $_POST['f_password'];
    
    // Modify the SQL query to match your database schema
    $sql_check_user_info = "SELECT *
                              FROM `users`
                            WHERE `uname` = '$uname'
                              AND `password` = '$pword'
                            ";
    $sql_result = mysqli_query($conn,$sql_check_user_info);
    $count_result = mysqli_num_rows($sql_result);
    
    if($count_result == 1){
        //existing user
        $row = mysqli_fetch_assoc($sql_result);
        
        //create session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['uname'];
        $_SESSION['user_cat'] = $row['user_cat'];
       
        // Redirect based on user category
        if($row['user_cat'] == 'A'){
            //admin
            header("location: admin");
            exit(); // Make sure to exit after redirection
        }
        else if($row['user_cat'] == 'U'){
            //common user
            header("location: common_user");
            exit(); // Make sure to exit after redirection
        }
    }
    else{
        // Redirect with error message
        header("location: login.php?error=user_not_exist");
        exit(); // Make sure to exit after redirection
    }
}
?>
