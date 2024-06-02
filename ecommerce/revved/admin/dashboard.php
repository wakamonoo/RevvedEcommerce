<?php
// Include the database connection file
include "../db.php";

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_GET['logout'])){
    session_destroy();
    header("location: ../index.php");
    die();
}
// Fetch total sales per item based on item price and quantity, for completed orders only
$sql_total_sales_per_item = "SELECT items.item_id, items.item, SUM(items.price * `order`.quantity) AS total_sales 
                             FROM `order` 
                             JOIN items ON `order`.item_id = items.item_id 
                             WHERE `order`.status = 5
                             GROUP BY items.item_id";
$result_total_sales_per_item = mysqli_query($conn, $sql_total_sales_per_item);

// Fetch total sales per day using the date_added column, but only for items with status 5
$sql_total_sales_per_day = "SELECT DATE(o.date_added) AS order_day, 
                            SUM(i.price * o.quantity) AS total_sales 
                            FROM `order` o
                            JOIN items i ON o.item_id = i.item_id
                            WHERE o.status = 5
                            GROUP BY DATE(o.date_added)";
$result_total_sales_per_day = mysqli_query($conn, $sql_total_sales_per_day);

$sql_total_sales_per_order = "SELECT o.order_ref_number, SUM(i.price * o.quantity) AS total_sales
                              FROM `order` o
                              JOIN items i ON o.item_id = i.item_id
                              WHERE o.status = 5
                              GROUP BY o.order_ref_number";
$result_total_sales_per_order = mysqli_query($conn, $sql_total_sales_per_order);


// Fetch total sales per user for items with status 5, including uname from users table
$sql_total_sales_per_user = "SELECT u.uname, SUM(i.price * o.quantity) AS total_sales 
                             FROM `order` o
                             JOIN users u ON o.user_id = u.user_id
                             JOIN items i ON o.item_id = i.item_id
                             WHERE o.status = 5
                             GROUP BY u.user_id";
$result_total_sales_per_user = mysqli_query($conn, $sql_total_sales_per_user);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: #212529;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 20px;
        }
        .table-container {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #343a40;
            border: 1px solid #212529;
            border-radius: 5px;
            color: #fff;
        }
        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #212529;
        }
        table th {
            background-color: #dc3545;
            font-weight: bold;
        }
        .total-sales-th {
            text-align: right;
        }
        h2, h4 {
            color: #dc3545;
        }
        .toolbar {
            background-color: #212529;
            padding: 10px;
            border-bottom: 2px solid #ffffff; /* Adding a white bottom outline */
        }
        .toolbar a {
            color: #ffffff;
            margin-right: 20px;
            text-decoration: none;
            font-size: 18px;
        }
        .toolbar a:hover {
            color: #dc3545; /* Red hover */
        }
    </style>
</head>
<body>
<div class="toolbar">
    <a href="index.php">Home</a>
    <a href="?logout">Logout</a>
    <a href="newitem.php">Add New Item</a>
    <a href="manage_order.php">Manage Order</a>
    <a href="dashboard.php">Dashboard</a>
</div>
    <div class="container">
        <!-- Display total sales per item -->
        <div class="table-container">
            <h2>Total Sales per Item</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="total-sales-th">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_total_sales_per_item)): ?>
                    <tr>
                        <td><?php echo $row['item']; ?></td>
                        <td class="total-sales-th">₱ <?php echo $row['total_sales']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Total Sales -->
            <div class="total-sales-th">
                <h4>Total Sales for Items:</h4>
                <?php
                // Calculate final total sales for items 
                $final_total_items = 0;
                mysqli_data_seek($result_total_sales_per_item, 0); // Reset result set pointer
                while ($row = mysqli_fetch_assoc($result_total_sales_per_item)) {
                    $final_total_items += $row['total_sales'];
                }
                echo "₱ " . $final_total_items;
                ?>
            </div>
        </div>

        <!-- Display total sales per day -->
        <div class="table-container">
            <h2>Total Sales per Day</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="total-sales-th">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_total_sales_per_day)): ?>
                    <tr>
                        <td><?php echo $row['order_day']; ?></td>
                        <td class="total-sales-th">₱ <?php echo $row['total_sales']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Total Sales -->
            <div class="total-sales-th">
                <h4>Total Sales for Days:</h4>
                <?php
                // Calculate final total sales for days
                $final_total_days = 0;
                mysqli_data_seek($result_total_sales_per_day, 0); // Reset result set pointer
                while ($row = mysqli_fetch_assoc($result_total_sales_per_day)) {
                    $final_total_days += $row['total_sales'];
                }
                echo "₱ " . $final_total_days;
                ?>
            </div>
        </div>

        <!-- Display total sales per order -->
        <div class="table-container">
            <h2>Total Sales per Order</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order Ref Number</th>
                        <th class="total-sales-th">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_total_sales_per_order)): ?>
                    <tr>
                        <td><?php echo $row['order_ref_number']; ?></td>
                        <td class="total-sales-th">₱ <?php echo $row['total_sales']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Total Sales -->
            <div class="total-sales-th">
                <h4>Total Sales for Orders:</h4>
                <?php
                // Calculate final total sales for orders
                $final_total_orders = 0;
                mysqli_data_seek($result_total_sales_per_order, 0); // Reset result set pointer
                while ($row = mysqli_fetch_assoc($result_total_sales_per_order)) {
                    $final_total_orders += $row['total_sales'];
                }
                echo "₱  " . $final_total_orders;
                ?>
            </div>
        </div>

        <!-- Display total sales per user -->
        <div class="table-container">
            <h2>Total Sales per User</h2>
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th class="total-sales-th">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result_total_sales_per_user)): ?>
                    <tr>
                        <td><?php echo $row['uname']; ?></td>
                        <td class="total-sales-th">₱ <?php echo $row['total_sales']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Total Sales -->
            <div class="total-sales-th">
                <h4>Total Sales for Users:</h4>
                <?php
                // Calculate final total sales for users
                $final_total_users = 0;
                mysqli_data_seek($result_total_sales_per_user, 0); // Reset result set pointer
                while ($row = mysqli_fetch_assoc($result_total_sales_per_user)) {
                    $final_total_users += $row['total_sales'];
                }
                echo "₱ " . $final_total_users;
                ?>
            </div>
        </div>
    </div>
</body>
</html>
