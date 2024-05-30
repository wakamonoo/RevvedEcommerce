<?php
include "../db.php";
session_start();
if(isset($_GET['item_id'], $_GET['cart_qty'])){
    $user_id = $_SESSION['user_id']; // Assuming 'user_id' is the correct session variable name
    $item_id = $_GET['item_id'];
    $item_qty = $_GET['cart_qty'];
    
    // Check if item is in stock
    $sql_check_stock = "SELECT stocks FROM `items` WHERE `item_id`='$item_id'";
    $result_check_stock = mysqli_query($conn, $sql_check_stock);
    $row_check_stock = mysqli_fetch_assoc($result_check_stock);
    $item_stock = $row_check_stock['stocks'];
    
    if($item_stock > 0) {
        $sql_add_to_cart = "INSERT INTO `order`
               (`user_id`, `item_id`, `quantity`, `status`)
               VALUES
               ('$user_id','$item_id','$item_qty','1')"; // Assuming 'status' column indicates the status of the order, and 'cart' is the status for items in the cart
        $execute_cart = mysqli_query($conn, $sql_add_to_cart);
        
        if($execute_cart){
            header("location: index.php?msg=item_{$item_id}_added_to_cart");
        }
    } else {
        // Item is out of stock, display message
        header("location: index.php?msg=item_{$item_id}_out_of_stock");
    }
}
?>
