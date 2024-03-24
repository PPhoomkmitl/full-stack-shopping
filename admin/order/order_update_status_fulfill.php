<?php
// Include database configuration

use PgSql\Result;

include_once '../../dbConfig.php';

// Function to update fullfill_status
function updateFullfillStatus($order_id, $newfullfill_status) {
    global $conn;
    if($newfullfill_status == 'Fulfilled' || $newfullfill_status == 'Unfulfiled' ) {
        // Update query
        $sql = "UPDATE orders SET fullfill_status = '$newfullfill_status' , shipping_status = 'Inprogress' , delivery_date = NOW() WHERE order_id = '$order_id'";
    }
    else {
        // Update query
        $sql = "UPDATE orders SET fullfill_status = 'Fulfilled' WHERE order_id = '$order_id'";

        $result = "SELECT * FROM order_details INNER JOIN product ON  product.ProID = order_details.ProID WHERE order_id = '$order_id'";
        
        while($row = Result)
        $sql = "UPDATE product SET StockQty = StockQty + 'quantity' WHERE order_id = '$order_id'";
    }
    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

// Function to update fullfill_status
function updateCanceledStatus($order_id, $newfullfill_status) {
    global $conn;

    // Update query
    $sql = "UPDATE orders SET shipping_status = '$newfullfill_status'  WHERE order_id = '$order_id'";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

// Check if order_id and newfullfill_status are sent via POST
if (isset($_POST['order_id']) && isset($_POST['newfullfill_status'])) {
    // Sanitize and validate input
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $newfullfill_status = mysqli_real_escape_string($conn, $_POST['newfullfill_status']);

    // Call the function to update status
    if($newfullfill_status == 'Fulfilled'){
        updateFullfillStatus($order_id, $newfullfill_status);
    } 
    else if ($newfullfill_status == 'canceled_fulfilled'){
        updateFullfillStatus($order_id, $newfullfill_status);
    }
    else {
        updateCanceledStatus($order_id, $newfullfill_status);
    }
    
} else {
    echo "Error: No data received";
}

// Close database connection
mysqli_close($conn);
?>
