<?php
// Assuming you've posted the form to this page using method="post"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once '../../dbConfig.php'; 

    $selectedProductsJSON = $_POST['selectedProducts'] ?? '';
    $selectedProducts = json_decode($selectedProductsJSON, true);

    $totalPrice = $_POST['totalProductPrice'];
    $cusID = $_POST['customerID'];
    $status = $_POST['status'];

    /* shipping_address */
    $recv_fname = $_POST['recv_fname'];
    $recv_lname = $_POST['recv_lname'];
    $recv_tel = $_POST['recv_tel'];
    $recv_address = $_POST['recv_address'];

    /* Order */
    $payer_fname = $_POST['payer_fname'];
    $payer_lname = $_POST['payer_lname'];
    $payer_tel = $_POST['payer_tel'];
    $addr1 = $_POST['address_line1'];

    // Create shipping_address info
    $insert_query_head = "INSERT INTO shipping_address (recipient_name, phone_number, address_line1) 
                        VALUES('$recv_fname.$recv_lname', '$recv_tel', '$recv_address')";
    $insert_result_head = mysqli_query($conn, $insert_query_head);

    if (!$insert_result_head) { 
        die("Error inserting into shipping_address: " . mysqli_error($conn));
    }

    // Get the inserted RecvID
    $recv_id = mysqli_insert_id($conn);

    // // Generate new NumID for shipping_address_detail
    // $resultDetail = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM shipping_address_detail WHERE CusID = '$cusID'");
    // $latestID = mysqli_fetch_assoc($resultDetail);
    // $lastID = $latestID['num_id'];

    // // Increment the numeric part
    // $newNumericPart = $lastID + 1;

    // // Format the complete NumID
    // $NumID_shipping_address = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

    // // Insert into shipping_address_detail
    // $insert_query_detail = "INSERT INTO shipping_address_detail (CusID, RecvID, NumID) VALUES('$cusID', '$recv_id', '$NumID_shipping_address')";
    // $insert_result_detail = mysqli_query($conn, $insert_query_detail);

    // if (!$insert_result_detail) {
    //     die("Error inserting shipping_address_detail: " . mysqli_error($conn));
    // }

    // Create Payer info
    $insert_query_head = "INSERT INTO billing_address(CusID, recipient_name, phone_number, address_line1) 
                        VALUES('$cusID','$payer_fname . $payer_lname', '$payer_tel', '$addr1')";
    $insert_result_head = mysqli_query($conn, $insert_query_head);

    if (!$insert_result_head) {
        die("Error inserting into billing_address: " . mysqli_error($conn));
    }

  


    // echo $TaxID;


    // // Generate new NumID for payer_detail
    // $resultDetail = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(NumID, 4) AS UNSIGNED)) AS num_id FROM payer_detail WHERE CusID = '$cusID'");
    // $latestID = mysqli_fetch_assoc($resultDetail);
    // $lastID = $latestID['num_id'];

    // // Increment the numeric part
    // $newNumericPart = $lastID + 1;

    // // Format the complete NumID
    // $NumID_payer = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

    // // Insert into payer_detail
    // $insert_query_detail = "INSERT INTO payer_detail (CusID, TaxID, NumID) VALUES('$cusID', '$TaxID', '$NumID_payer')";
    // $insert_result_detail = mysqli_query($conn, $insert_query_detail);

    // if (!$insert_result_detail) {
    //     die("Error inserting payer_detail: " . mysqli_error($conn));
    // }

    // Generate new RECEIVE ID
    // $result = mysqli_query($conn, "SELECT MAX(order_id) AS order_id FROM orders");
    // $row = mysqli_fetch_assoc($result);
    // $lastID = $row['order_id'];
    // $newNumericPart = $lastID + 1;
    // $order_id = $newNumericPart;

    // Insert into orders table
    $stmt = mysqli_query($conn, "INSERT INTO orders(CusID, order_date, shipping_status, fullfill_status , total_price , billing_address_id , shipping_address_id , image_slip_id)
        VALUES ($cusID , NOW(),'$status', 'Unfulfilled' ,'$totalPrice', NULL , '$recv_id' , null);");

    if (!$stmt) {
        die("Error inserting into orders: " . mysqli_error($conn));
    }

    foreach ($selectedProducts as $product) {
        // Generate new NumID for order_details
        // $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM order_details WHERE order_id = '$order_id'");
        // $latestID = mysqli_fetch_assoc($resultDetail);
        // $lastID = $latestID['num_id'];
        // $numericPart = intval(substr($lastID, 3));
        // $newNumericPart = $numericPart + 1;
        // $NumID_receive = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        // Insert into order_details table
        $stmt = mysqli_query($conn, "INSERT INTO order_details (order_id, ProID , quantity, subtotal_price) VALUES ('$order_id', '{$product['productId']}', '{$product['quantity']}') , 0.0");

        // Update Stock and OnHands
        $stmt = mysqli_query($conn, "UPDATE product SET StockQty = StockQty - '{$product['quantity']}', OnHands = OnHands - '{$product['quantity']}' WHERE ProID = '{$product['productId']}'");
    }

    // Calculate total with tax
    $TotalWithTax = $totalPrice * 1.07;

    // Update total with tax in orders table
    $stmt = mysqli_query($conn, "UPDATE orders SET TotalPrice ='$TotalWithTax' WHERE order_id ='$order_id'");

    // Redirect to order_index.php
    header("location: ./order_index.php");
    exit;
}
?>
