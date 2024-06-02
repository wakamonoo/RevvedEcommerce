<?php
if(isset($_POST['order_ref_number'])){
    include_once "../db.php";
    $orderrefnum = $_POST['order_ref_number'];
    
     $sql_update_order="UPDATE `order`
                                SET `status` = '0'
                               WHERE `order_ref_number` = '$orderrefnum';
                               ";
         $try = mysqli_query($conn, $sql_update_order);
     
    if($try){
          header("location: tracking.php?page=myorder");   
    }
}
?>
