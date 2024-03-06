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

    /* Order */
    $payer_fname = $_POST['payer_fname'];
    $payer_lname = $_POST['payer_lname'];
    $payer_tel = $_POST['payer_tel'];

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

    // Generate new RECEIVE ID
    $result = mysqli_query($conn, "SELECT MAX(RecID) AS rec_id FROM receive");
    $row = mysqli_fetch_assoc($result);
    $lastID = $row['rec_id'];
    $numericPart = intval(substr($lastID, 6));
    $newNumericPart = $numericPart + 1;
    $RecID = 'rec_id' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

    // Insert into receive table
    $stmt = mysqli_query($conn, "INSERT INTO receive(RecID, OrderDate, CusID, TotalPrice, RecvID , TaxID ,Status)
        VALUES ('$RecID', NOW(),'$cusID','$totalPrice', '$recv_id', '$TaxID' ,'$status');");

    if (!$stmt) {
        die("Error inserting into receive: " . mysqli_error($conn));
    }

    foreach ($selectedProducts as $product) {
        // Generate new NumID for receive_detail
        $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM receive_detail WHERE RecID = '$RecID'");
        $latestID = mysqli_fetch_assoc($resultDetail);
        $lastID = $latestID['num_id'];
        $numericPart = intval(substr($lastID, 3));
        $newNumericPart = $numericPart + 1;
        $NumID_receive = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert into receive_detail table
        $stmt = mysqli_query($conn, "INSERT INTO receive_detail (RecID, NumID, ProID, Qty) VALUES ('$RecID', '$NumID_receive', '{$product['productId']}', '{$product['quantity']}')");

        // Update Stock and OnHands
        $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '{$product['quantity']}', OnHands = OnHands - '{$product['quantity']}' WHERE ProID = '{$product['productId']}'");
    }

    // Calculate total with tax
    $TotalWithTax = $totalPrice * 1.07;

    // Update total with tax in receive table
    $stmt = mysqli_query($conn, "UPDATE receive SET TotalPrice ='$TotalWithTax' WHERE RecID ='$RecID'");

    // Redirect to order_index.php
    header("location: ./order_index.php");
    exit;
}
?>
