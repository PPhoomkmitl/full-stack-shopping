<?php /* get connection */
    header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_stock'])){
        $code = $_POST['id_stock'];
        /* run delete */
        // $stmt = odbc_prepra($conn, "DELETE FROM stock WHERE IDCust='$code'");
        $stmt = mysqli_query($conn,"DELETE FROM product WHERE ProID ='$code'");
        /* check for errors */
        if (!$stmt) { 
            /* error */
            echo "Error deleting data with IDCust = '$code'";
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
            echo "<a href='stock_index.php' 
                style='
                padding: 9px 14px;
                color: #ef476f;             
                text-decoration: none;
                margin-right: 5px;
                '>กลับหน้าหลัก</a>";
        }
    }
    else if (isset($_POST['list_id_stock'])){
        $list_ids = $_POST['list_id_stock'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt = mysqli_query($conn, "DELETE FROM product WHERE ProID ='$code'");
    
            /* check for errors */
            if (!$stmt) {
                /* error */
                echo "Error deleting data with IDCust = '$code'";
            } else {
                echo "Delete data with IDProduct = <font color=red> '$code' </font> is Successful.<br>";
            }
        }
        echo "<a href='stock_index.php' 
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