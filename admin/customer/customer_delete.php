<?php /* POST connection */
    header( "location: ./customer_index.php");
    include_once '../../dbConfig.php'; 
    if (isset($_POST['id_customer'])){
        $code = $_POST['id_customer'];


        /* run delete */
        /* run delete queries */
        $stmt_account_customer = mysqli_query($conn, "DELETE FROM customer_account WHERE CusID='$code'");
        $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");
        /* check for errors */
        if (!$stmt_customer) {
            echo "Error: " . mysqli_error($conn);
        } else {
            echo "Delete data = <font color=red> '$code' </font> is Successful. <br>";
        }
    }
    else if (isset($_POST['list_id_customer'])){
        $list_ids = $_POST['list_id_customer'];  
        $codesArray = explode(',', $list_ids);
        foreach ($codesArray as $code) {
            $code = mysqli_real_escape_string($conn, $code);
            $stmt_account_customer = mysqli_query($conn, "DELETE FROM customer_account WHERE CusID='$code'");
            $stmt_customer = mysqli_query($conn, "DELETE FROM customer WHERE CusID='$code'");

            /* check for errors */
            if (!$stmt_customer) {
                echo "Error: " . mysqli_error($conn);
            }

            /* check for errors */
            else {
                    echo "Delete data with CusID = <font color=red> '$code' </font> is Successful.<br>";
                }
            }
    }
    /* close connection */
    mysqli_close($conn);
?>