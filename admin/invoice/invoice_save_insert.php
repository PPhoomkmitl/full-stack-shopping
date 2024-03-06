<?php
    // Assuming you've posted the form to this page using method="post"
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include_once '../../dbConfig.php';  

        $selectedProductsJSON = $_POST['selectedProducts'] ?? '';
        $selectedProducts = json_decode($selectedProductsJSON, true);

        $totalPrice = $_POST['totalProductPrice'];
        $cusID = $_POST['customerName'];
        $status = $_POST['status'];

        /* Receiver */
        $recv_fname = $_POST['recv_fname'];
        $recv_lname = $_POST['recv_lname'];
        $recv_tel = $_POST['recv_tel'];
        $recv_address = $_POST['recv_address'];

        // Now $selectedProducts contains the array, and you can use it as needed
        print_r($selectedProducts); // Example: Print the array for testing
        echo $totalPrice;
        echo $cusID;
        echo $status;

        /* -----------------------------------Recevier------------------------------- */
        
        // Create Receiver info
        $insert_query_head = "INSERT INTO receiver (RecvFName, RecvLName, Tel, Address) 
        VALUES('$recv_fname', '$recv_lname', '$recv_tel', '$recv_address')";
        $insert_result_head = mysqli_query($conn, $insert_query_head);

        if (!$insert_result_head) {
            die("Error inserting into receiver: " . mysqli_error($conn));
        }

        // Get the inserted RecvID
        $recv_id = mysqli_insert_id($conn);

        // Generate new NumID for receiver_detail
        $resultDetail = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM receiver_detail WHERE CusID = '$cusID'");
        $latestID = mysqli_fetch_assoc($resultDetail);
        $lastID = $latestID['num_id'];

        // Increment the numeric part
        $newNumericPart = $lastID + 1;

        // Format the complete NumID
        $NumID_receiver = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert into receiver_detail
        $insert_query_detail = "INSERT INTO receiver_detail (CusID, RecvID, NumID) VALUES('$cusID', '$recv_id', '$NumID_receiver')";
        $insert_result_detail = mysqli_query($conn, $insert_query_detail);

        if (!$insert_result_detail) {
            die("Error inserting receiver_detail: " . mysqli_error($conn));
        }

        /* ------------------------------------------------------------------------ */


        // Generate new invoice ID
        $result = mysqli_query($conn, "SELECT MAX(InvID) AS inv_id FROM invoice");
        $row = mysqli_fetch_assoc($result);
        $lastID = $row['inv_id'];
        $numericPart = intval(substr($lastID, 6));
        $newNumericPart = $numericPart + 1;
        $InvID = 'inv_id'.str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert invoice record
        $stmt = mysqli_query($conn, "INSERT INTO invoice(InvID, Period ,CusID, TotalPrice , Status)
            VALUES ('$InvID', NOW(),'$cusID','$totalPrice','$status');");

        foreach ($selectedProducts as $product) {
            
            // Generate new NumID
            $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'");
            $latestID = mysqli_fetch_assoc($resultDetail);
            $lastID = $latestID['num_id'];
            $numericPart = intval(substr($lastID, 3));
            $newNumericPart = $numericPart + 1; 
            $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
            echo $lastID."    ".$numericPart."    ".$newNumericPart."    ".$NumID;
            echo "SELECT MAX(NumID) AS num_id FROM invoice_detail WHERE InvID = '$InvID'";
            
            $proID = $product['productId'];
            $Qty = $product['quantity'];
            
        
            // Insert invoice_detail record
            $stmt = mysqli_query($conn, "INSERT INTO invoice_detail (InvID, NumID, ProID, Qty) VALUES ('$InvID', '$NumID', '$proID', '$Qty')");

            // Update Status
            // $stmt = mysqli_query($conn, "UPDATE invoice_detail SET Status = 'Paid' WHERE invID ='$invID'");

            // Update Stock and OnHands
            $stmt = mysqli_query($conn, "UPDATE product SET OnHands = OnHands + '$Qty' WHERE ProID ='$proID'");
               
        }
        header( "location: ./invoice_index.php");  
    }
?>