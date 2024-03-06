<?php
// get connection
include_once '../../dbConfig.php'; 

if (isset($_POST['total_id_order'])) {
    $code = $_POST['total_id_order'];

    // run delete
    $check_id = mysqli_query($conn, "SELECT * FROM receive WHERE RecID ='$code'");
    $row = mysqli_fetch_assoc($check_id);
    $recv_id = $row['RecvID'];
    $tax_id = $row['TaxID'];

    // delete related records in receiver_detail
    mysqli_query($conn, "DELETE FROM receiver_detail WHERE RecvID ='$recv_id'");
    // delete related records in receiver
    mysqli_query($conn, "DELETE FROM receiver WHERE RecvID ='$recv_id'");

    // delete related records in payer_detail
    mysqli_query($conn, "DELETE FROM payer_detail WHERE TaxID ='$tax_id'");
    // delete related records in payer
    mysqli_query($conn, "DELETE FROM payer WHERE TaxID ='$tax_id'");

    // delete from receive_detail
    mysqli_query($conn, "DELETE FROM receive_detail WHERE RecID ='$code'");
    // delete from receive
    mysqli_query($conn, "DELETE FROM receive WHERE RecID ='$code'");

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
        $check_id = mysqli_query($conn, "SELECT * FROM receive WHERE RecID ='$code'");
        $row = mysqli_fetch_assoc($check_id);
        $recv_id = $row['RecvID'];
        $tax_id = $row['TaxID'];

        // delete related records in receiver_detail
        mysqli_query($conn, "DELETE FROM receiver_detail WHERE RecvID ='$recv_id'");

        // delete related records in payer_detail
        mysqli_query($conn, "DELETE FROM payer_detail WHERE TaxID ='$tax_id'");

        // delete from receive_detail
        mysqli_query($conn, "DELETE FROM receive_detail WHERE RecID ='$code'");


        // delete from receive_detail
        mysqli_query($conn, "DELETE FROM receive_detail WHERE RecID ='$code'");
        
        // delete from receive
        mysqli_query($conn, "DELETE FROM receive WHERE RecID ='$code'");

        

        // delete related records in receiver
        mysqli_query($conn, "DELETE FROM receiver WHERE RecvID ='$recv_id'");

        // delete related records in payer
        mysqli_query($conn, "DELETE FROM payer WHERE TaxID ='$tax_id'");

        
        

        // check for errors
        echo "Delete data with RecID = <font color=red> '$code' </font> is Successful.<br>";
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
