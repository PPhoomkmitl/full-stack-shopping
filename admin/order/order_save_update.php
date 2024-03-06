<?php /* get connection */
    header( "location: ./order_index.php");  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include_once '../../dbConfig.php';  
        $RecID = $_POST['RecID'];
        $RecvID = $_POST['id_receiver'];
        $TaxID = $_POST['id_payer'];
        $Qty = $_POST['Qty'];
        $ProID = $_POST['ProID'];
     
        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        echo $totalPrice;
        echo $cusID;
        echo $status;
        echo $RecvID;
        print_r($ProID);
        
        /* Receiver */
        $recv_fname = $_POST['recv_fname'];
        $recv_lname = $_POST['recv_lname'];
        $recv_tel = $_POST['recv_tel'];
        $recv_address = $_POST['recv_address'];

        /* Payer */
        $payer_fname = $_POST['payer_fname'];
        $payer_lname = $_POST['payer_lname'];
        $payer_tel = $_POST['payer_tel'];

        // Insert RECEIVE record
        if($status != 'Pending'){
            mysqli_query($conn, "UPDATE receive SET DeliveryDate = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE RecID ='$RecID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

            mysqli_query($conn, "UPDATE payer SET PayerFName = '$payer_fname', PayerLName = '$payer_lname' , Tel = '$payer_tel'
            WHERE TaxID ='$TaxID'");

        }
        else{
            mysqli_query($conn, "UPDATE receive SET TotalPrice = '$totalPrice'
            WHERE RecID ='$RecID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

            mysqli_query($conn, "UPDATE payer SET PayerFName = '$payer_fname', PayerLName = '$payer_lname' , Tel = '$payer_tel'
            WHERE TaxID ='$TaxID'");

        }

        if (count($ProID) == count($Qty)) {
                $totalItems = count($ProID);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proID = $ProID[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM receive_detail WHERE RecID = '$RecID' AND ProID ='$proID'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty + '$oldQty', OnHands = OnHands + '$oldQty' WHERE ProID ='$proID'");

                    // Update Stock and OnHands
                    $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$qty', OnHands = OnHands - '$qty' WHERE ProID ='$proID'");

                    $stmt = mysqli_query($conn, "UPDATE receive_detail SET Qty = '$qty'
                    WHERE ProID ='$proID' AND RecID ='$RecID'");


                    echo "ProID: $proID, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ ProID และ Qty ไม่เท่ากัน";
        }             
        }
        
?>