<?php
function createInvoiceAPI($cusID, $orderId, $tax_id) {
    // API endpoint URL
    $apiEndpoint = 'http://localhost:8000/invoice/createInvoice';
    // Prepare the data to be sent in the request body
    $postData = array(
        'cusID' => $cusID,
        'orderId' => $orderId,
        'tax_id' => $tax_id
        // Add other necessary data fields here
    );

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),

    ));

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        $errorMessage = curl_error($curl);
        // Handle error appropriately, e.g., log it
        echo "cURL Error: $errorMessage";
    } else {
        // Process the API response
        // You may want to handle the response based on the API's expected output
        echo "API Response: $response";
    }

    // Close cURL session
    curl_close($curl);
}
    // function createInvoice($cusID, $orderId, $conn , $tax_id)
    // {

    //     $totalAmount = calculateTotalAmount($orderId, $conn);

    //     $invoiceInsertQuery = "
    //         INSERT INTO invoice (CusID, total_amount, tax_id)
    //         VALUES ('$cusID', '$totalAmount',$tax_id)
    //     ";
    //     mysqli_query($conn, $invoiceInsertQuery);
    //     $invoiceId = $conn->insert_id;

    //     $invoiceUpdateOrders = "
    //         UPDATE orders
    //         SET invoice_id = '$invoiceId'
    //         WHERE order_id = '$orderId';
    //     ";
    //     mysqli_query($conn, $invoiceUpdateOrders);

    //     echo $invoiceId;

    //     // Insert invoice details
    //     insertInvoiceDetails($orderId, $invoiceId, $conn);
    // }

    // function calculateTotalAmount($orderId, $conn)
    // {
    //     $totalAmount = 0;

    //     $orderDetailsQuery = "SELECT quantity, subtotal_price FROM order_details WHERE order_id = $orderId";
    //     $orderDetailsResult = mysqli_query($conn, $orderDetailsQuery);

    //     if ($orderDetailsResult) {
    //         while ($row = mysqli_fetch_assoc($orderDetailsResult)) {
    //             $subtotalPrice = $row['subtotal_price'];
    //             $totalAmount += $subtotalPrice;
    //         }
    //         $totalAmount = ($totalAmount * 0.07) + $totalAmount;
    //     }
    //     echo $totalAmount;
    //     return $totalAmount;
    // }

    // function insertInvoiceDetails($orderId, $invoiceId, $conn)
    // {
    //     $orderDetailsQuery = "SELECT * FROM order_details WHERE order_id = $orderId";
    //     $orderDetailsResult = mysqli_query($conn, $orderDetailsQuery);

    //     if ($orderDetailsResult) {
    //         while ($row = mysqli_fetch_assoc($orderDetailsResult)) {
    //             $productId = $row['ProID'];
    //             $quantity = $row['quantity'];
    //             $pricePerUnit = $row['subtotal_price'] / $quantity;
    //             $totalPrice = $row['subtotal_price'];

    //             $invoiceDetailInsertQuery = "
    //                 INSERT INTO invoice_detail (invoice_id, ProID , quantity, price_per_unit, total_price)
    //                 VALUES ('$invoiceId', '$productId', '$quantity', '$pricePerUnit', '$totalPrice')
    //             ";
    //             mysqli_query($conn, $invoiceDetailInsertQuery);
    //         }
    //     }
    // }
?>
