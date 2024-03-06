<?php /* get connection */
    header( "location: ./invoice_index.php");  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include_once '../../dbConfig.php';  
        $InvID = $_POST['InvID'];
        $RecvID = $_POST['id_receiver'];
        $Qty = $_POST['Qty'];
        $ProID = $_POST['ProID'];
     
        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        // Now $selectedProducts contains the array, and you can use it as needed
        echo $totalPrice;
        echo $cusID;
        echo $status;
        print_r($ProID);
        echo $InvID;

        /* Receiver */
        $recv_fname = $_POST['recv_fname'];
        $recv_lname = $_POST['recv_lname'];
        $recv_tel = $_POST['recv_tel'];
        $recv_address = $_POST['recv_address'];
        

        // Insert invoice Invord
        if($status != 'Unpaid'){
            mysqli_query($conn, "UPDATE invoice SET Period = NOW() , TotalPrice = '$totalPrice', Status ='$status'
            WHERE InvID ='$InvID'");

            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");

        }
        else{
            mysqli_query($conn ,"UPDATE invoice SET TotalPrice = '$totalPrice' 
            WHERE InvID ='$InvID'");
            
            mysqli_query($conn, "UPDATE receiver SET RecvFName = '$recv_fname', RecvLName = '$recv_lname' , Tel = '$recv_tel'
            WHERE RecvID ='$RecvID'");
        
        }

        if (count($ProID) == count($Qty)) {
                $totalItems = count($ProID);
            
                for ($i = 0; $i < $totalItems; $i++) {
                    $proID = $ProID[$i];
                    $qty = $Qty[$i];

                    $cur = "SELECT * FROM invoice_detail WHERE InvID = '$InvID' AND ProID ='$proID'";
                    $msresults = mysqli_query($conn, $cur);
                    $row = mysqli_fetch_assoc($msresults);
                    $oldQty = $row['Qty'];
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands - '$oldQty' WHERE ProID ='$proID'");

                    // Update Stock and OnHands
                    $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands + '$qty' WHERE ProID ='$proID'");

                    $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Qty = '$qty'
                    WHERE ProID ='$proID' AND InvID ='$InvID'");


                    echo "ProID: $proID, Qty: $qty<br>";
                }
        } else {
            echo "ขนาดของอาร์เรย์ ProID และ Qty ไม่เท่ากัน";
        }             
        }
        
?>