<?php
include('./component/session.php'); 
include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');
include_once '../dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    /* Manage Order to send */
    if(isset($_POST['id_invoice'])&&isset($_POST['id_customer'])){
        $cusID = $_POST['id_customer'];
        $invID = $_POST['id_invoice'];
        $recvID = $_POST['id_receiver'];

        /* Order */
        $payer_fname = $_POST['fname'];
        $payer_lname = $_POST['lname'];
        $payer_tel = $_POST['tel'];

        echo $cusID;
        echo $invID;
        echo $recvID;


        // Create Payer info
        $result = mysqli_query($conn, "SELECT MAX(TaxID) AS tax_id FROM payer");
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['tax_id'];
        $numericPart = intval(substr($lastID, 3));
        $newNumericPart = $numericPart + 1;
        $TaxID = 'Tax' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        $insert_query_head = "INSERT INTO payer(TaxID, PayerFName, PayerLName, Tel) 
                            VALUES('$TaxID', '$payer_fname', '$payer_lname', '$payer_tel')";
        $insert_result_head = mysqli_query($conn, $insert_query_head);

        if (!$insert_result_head) {
            die("Error inserting into payer: " . mysqli_error($conn));
        }

    
        echo $TaxID;


        // Generate new NumID for payer_detail
        $resultDetail = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM payer_detail WHERE CusID = '$cusID'");
        $latestID = mysqli_fetch_assoc($resultDetail);
        $lastID = $latestID['num_id'];

        // Increment the numeric part
        $newNumericPart = $lastID + 1;
    
        // Format the complete NumID
        $NumID_payer = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert into payer_detail
        $insert_query_detail = "INSERT INTO payer_detail (CusID, TaxID, NumID) VALUES('$cusID', '$TaxID', '$NumID_payer')";
        $insert_result_detail = mysqli_query($conn, $insert_query_detail);

        if (!$insert_result_detail) {
            die("Error inserting payer_detail: " . mysqli_error($conn));
        }


        $check_query = mysqli_query($conn, "SELECT InvID , CusID , TotalPrice FROM invoice WHERE CusID = '$cusID' AND InvID = '$invID'");
        $row = mysqli_fetch_assoc($check_query);
        $totalPrice = $row['TotalPrice'];

        if(mysqli_num_rows($check_query) > 0){
            mysqli_data_seek($check_query, 0);

            // Generate new RECEIVE ID
            $result = mysqli_query($conn, "SELECT MAX(RecID) AS rec_id FROM receive");
            $row = mysqli_fetch_assoc($result);
            $lastID = $row['rec_id'];
            $numericPart = intval(substr($lastID, 6));
            $newNumericPart = $numericPart + 1;
            $RecID = 'rec_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

            echo $RecID;

            // Insert RECEIVE record
            $stmt = mysqli_query($conn, "INSERT INTO receive(RecID, OrderDate , TaxID , RecvID ,CusID, TotalPrice , Status)
                VALUES ('$RecID', NOW() , '$TaxID' , '$recvID' ,'$cusID','$totalPrice','Pending');");

            // ACCESS LOG
            $uid = $cusID;
            $productId = "";
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ipAddress = $_SERVER['REMOTE_ADDR'];
            }
            $callingFile = __FILE__;
            $action = 'INSERT'; // Static Change Action
            CallLog::callLog($ipAddress, $conn, $uid, $productId, $callingFile, $action);
            //END LOG
  
            while (true) {
                // Generate new NumID
                $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
                $latestID = mysqli_fetch_assoc($resultDetail);
                $lastID = $latestID['num_id'];
                $numericPart = intval(substr($lastID, 3));
                $newNumericPart = $numericPart + 1; 
                $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

                echo $NumID;

                $resultDetail = mysqli_query($conn, "SELECT invID , ProID , Qty FROM invoice_detail WHERE invID = '$invID' AND NumID = '$NumID'");
                
                echo mysqli_num_rows($resultDetail);
               
                if ($resultDetail && mysqli_num_rows($resultDetail) > 0) {
                    $invoice_detail = mysqli_fetch_assoc($resultDetail);
                    $proID = $invoice_detail['ProID'];
                    $Qty = $invoice_detail['Qty'];
                    $invID = $invoice_detail['invID'];

                    // Insert invoice_detail record
                    $stmt = mysqli_query($conn, "INSERT INTO receive_detail (RecID, NumID, ProID, Qty) VALUES ('$RecID', '$NumID', '$proID', '$Qty')");

                    // Update Status
                    $stmt = mysqli_query($conn, "UPDATE invoice SET Status = 'Paid' WHERE invID ='$invID'");

                    $stmt2 = mysqli_query($conn,"SELECT StockQty from product where ProID = '$proID'");
                    $stockQtyRow = mysqli_fetch_assoc($stmt2);
                    $stockQty = $stockQtyRow['StockQty'];

                    // Update Stock and OnHands
                    if(isset($_SESSION['guest']) && ($stockQty-$Qty) >= 0 ){
                        $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$Qty', OnHands = OnHands WHERE ProID ='$proID'");
                    } else if(($stockQty-$Qty) >= 0) {
                        $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '$Qty', OnHands = OnHands - '$Qty' WHERE ProID ='$proID'");
                    }
                } else {
                    // No more matching records found, break the loop
                    break;
                }        
            }
            $TotalWithTax = $totalPrice * 1.07;

            
            /*-------------------------------------*/
            $stmt = mysqli_query($conn, "UPDATE receive SET TotalPrice ='$TotalWithTax'
            WHERE RecID ='$RecID'");
            /*-------------------------------------*/

            // Form submission with hidden values
            echo "<form id='auto_submit_form' method='post' action='bill.php'>
            <input type='hidden' name='id_order' value='$RecID'>
            </form>";
    
            // Use JavaScript to trigger form submission
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('auto_submit_form').submit();
            });
            </script>";
            exit();
        }

    }
}
?>