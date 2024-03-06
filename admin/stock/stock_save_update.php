<?php /* get connection */
    header( "location: ./stock_index.php");
    include_once '../../dbConfig.php'; 
    $a1 = $_POST['a1'];
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];

    $stmt = mysqli_query($conn, "UPDATE product SET ProName = '$a2', PricePerUnit ='$a3', StockQty ='$a4'
        WHERE ProID ='$a1'");

    if (!$stmt) {
        echo "Error";
    } else {
        echo "Update data = <font color=red> '$a1' </font> is Successful.";
    }
    mysqli_close($conn);
?>