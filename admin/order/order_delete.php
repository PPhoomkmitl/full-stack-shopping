<?php
// get connection
include_once '../../dbConfig.php'; 

if (isset($_POST['total_id_order'])) {
    $code = $_POST['total_id_order'];

    // run delete
    $check_id = mysqli_query($conn, "SELECT * FROM orders WHERE order_id ='$code'");
    $row = mysqli_fetch_assoc($check_id);
    $recv_id = $row['shipping_address_id'];
    $tax_id = $row['billing_address_id'];

    // delete related records in shipping_address
    mysqli_query($conn, "DELETE FROM shipping_address WHERE address_id ='$recv_id'");
    // delete related records in shipping_address
    mysqli_query($conn, "DELETE FROM shipping_address WHERE address_id ='$recv_id'");

    // delete related records in payer_detail
    mysqli_query($conn, "DELETE FROM payer_detail WHERE address_id ='$tax_id'");
    // delete related records in billing_address
    mysqli_query($conn, "DELETE FROM billing_address WHERE address_id ='$tax_id'");

    // delete from order_details
    mysqli_query($conn, "DELETE FROM order_details WHERE order_id ='$code'");
    // delete from orders
    mysqli_query($conn, "DELETE FROM orders WHERE order_id ='$code'");

    // check for errors
    echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
    echo "<a href='order_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>กลับหน้าหลัก</a>";
} else if (isset($_POST['list_id_order'])) {
    $list_ids = $_POST['list_id_order'];  
    $codesArray = explode(',', $list_ids);

    foreach ($codesArray as $code) {
        $code = mysqli_real_escape_string($conn, $code);

        // run delete
        $check_id = mysqli_query($conn, "SELECT * FROM orders WHERE order_id ='$code'");
        $row = mysqli_fetch_assoc($check_id);
        $recv_id = $row['shipping_address_id'];
        $tax_id = $row['billing_address_id'];

        // delete related records in shipping_address
        mysqli_query($conn, "DELETE FROM shipping_address WHERE address_id ='$recv_id'");

        // delete related records in payer_detail
        mysqli_query($conn, "DELETE FROM payer_detail WHERE address_id ='$tax_id'");

        // delete from order_details
        mysqli_query($conn, "DELETE FROM order_details WHERE order_id ='$code'");


        // delete from orders
        mysqli_query($conn, "DELETE FROM orders WHERE order_id ='$code'");

    
        // delete related records in billing_address
        mysqli_query($conn, "DELETE FROM billing_address WHERE address_id ='$tax_id'");

        
        // check for errors
        echo "Delete data with order_id = <font color=red> '$code' </font> is Successful.<br>";
    }
    echo "<a href='order_index.php' 
    style='
    padding: 9px 14px;
    color: #ef476f;             
    text-decoration: none;
    margin-right: 5px;
    '>กลับหน้าหลัก</a>";
}

// close connection
mysqli_close($conn);
?>
