<?php /* get connection */
    header( "location: ./invoice_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['total_id_invoice'])){
        $code = $_POST['total_id_invoice'];
        /* run delete */
        $stmt1 = mysqli_query($conn, "DELETE FROM invoice_detail WHERE InvID ='$code'");
        $stmt2 = mysqli_query($conn, "DELETE FROM invoice WHERE InvID ='$code'");
        /* check for errors */
        if (!$stmt) { 
            /* error */
            echo "Error deleting data with InvID = '$code'";
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
            echo "<a href='invoice_index.php' 
                style='
                padding: 9px 14px;
                color: #ef476f;             
                text-decoration: none;
                margin-right: 5px;
                '>กลับหน้าหลัก</a>";
        }
    }
    else if (isset($_POST['list_id_invoice'])){
        $list_ids = $_POST['list_id_invoice'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt1 = mysqli_query($conn, "DELETE FROM invoice_detail WHERE InvID ='$code'");
            $stmt2 = mysqli_query($conn, "DELETE FROM invoice WHERE InvID ='$code'");
    
            /* check for errors */
            if (!$stmt1 && !$stmt2) {
                /* error */
                echo "Error deleting data with InvID = '$code'";
            } else {
                echo "Delete data with InvID = <font color=red> '$code' </font> is Successful.<br>";
            }
        }
        echo "<a href='invoice_index.php' 
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