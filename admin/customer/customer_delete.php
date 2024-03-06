<?php /* POST connection */
    // header( "location: ./customer_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_customer'])){
        $code = $_POST['id_customer'];
        $recvID = $_POST['id_receiver'];

        /* run delete */
        /* run delete queries */
        $stmt_receiver_detail = mysqli_query($conn, "DELETE FROM receiver_detail WHERE CusID='$code'");
        $stmt_receiver = mysqli_query($conn, "DELETE FROM receiver WHERE RecvID='$recvID'");
        $stmt_account_customer = mysqli_query($conn, "DELETE FROM customer_account WHERE CusID='$code'");
        $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");
        /* check for errors */
        if (!$stmt_receiver_detail || !$stmt_receiver || !$stmt_customer) {
            echo "Error: " . mysqli_error($conn);
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
            echo "<a href='customer_index.php' 
                style='
                padding: 9px 14px;
                color: #ef476f;             
                text-decoration: none;
                margin-right: 5px;
                '>กลับหน้าหลัก</a>";
        }
    }
    else if (isset($_POST['list_id_customer'])){
        $list_ids = $_POST['list_id_customer'];  
        $recvID = $_POST['id_receiver'];
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt_receiver_detail = mysqli_query($conn, "DELETE FROM receiver_detail WHERE CusID='$code'");
            $stmt_receiver = mysqli_query($conn, "DELETE FROM receiver WHERE RecvID='$recvID'");
            $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");

            /* check for errors */
            if (!$stmt_receiver_detail || !$stmt_receiver || !$stmt_customer) {
                echo "Error: " . mysqli_error($conn);
            }

            /* check for errors */
            else {
                    echo "Delete data with CusID = <font color=red> '$code' </font> is Successful.<br>";
                }
            }
        echo "<a href='customer_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>กลับหน้าหลัก</a>";
    }
    /* close connection */
    mysqli_close($conn);
?>