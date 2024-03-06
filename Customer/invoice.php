<?php include('./component/session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 1150px;
            margin: 50px auto;
            border: 2px solid #4CAF50;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        .invoice-header {
            text-align: center;
            color: #4CAF50;
        }

        .invoice-details,
        .customer-details,
        .invoice-table,
        .invoice-total {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        .invoice-total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }

        h1, h2 {
            color: #333;
        }

        .buy-button-container {
            text-align: right;
        }

        .buy-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            margin-left:15px;
        }

        .buy-button:hover {
            background-color: #2980b9;
        }

        .container_order {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .customer-details{
            width: 400px;
        }
        
        .invoice-details {
            width: 280px;;
            align-self: flex-end;
            text-align: right;
            margin-bottom: 60px;
        }

        .action-buttons {
            display: flex;
            justify-content: end;
            margin-top: 45px;
        }

        .action-button {
            display: inline-block;
        }

        .action-button button[type='submit'] {
            background-color: #364856;
            cursor: pointer;
            border: none;
            width: 73px;
            height: 33px; 
        }

        .action-button button[type='submit']:hover {
            background-color: #9BA4AB;
            cursor: pointer;
            border: none;
            width: 73px;
            height: 33px; 
        }

        .action-button img {
            width: 20px;
            height: 20px; 
        }


       

    </style>
</head>
<body>
<?php include('./component/accessNavbar.php')?>
<?php    
    include_once '../dbConfig.php'; 
    echo "<div class='invoice-container'>";
    echo "<div class='body-container'>";
    echo "<div class='invoice-header'>
            <h1>Invoice</h1>
          </div>";

    $recv_id = $_POST['id_receiver'];
    $uid = $_SESSION['id_username'];
  
    if(isset($_POST['id_invoice'])){
        if (isset($_SESSION['cart'])) {
            $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM receiver INNER JOIN receiver_detail ON receiver.RecvID =  receiver_detail.RecvID WHERE receiver_detail.CusID = '$uid' AND receiver_detail.RecvID = '$recv_id' ");  
            $customerDetails = mysqli_fetch_array($customerDetailsQuery);
            $customerId = $uid;
        }
        else {
            echo $recv_id;
            $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM receiver INNER JOIN receiver_detail ON receiver.RecvID =  receiver_detail.RecvID WHERE receiver_detail.CusID = '$uid' AND receiver_detail.RecvID = '$recv_id'");  
            $customerDetails = mysqli_fetch_array($customerDetailsQuery);
            $customerId = $uid;
        }
      
        $invoiceId = $_POST['id_invoice'];

        echo "<div class='container_order'>";
        echo "<div class='customer-details'>
                    <h2>Customer Details</h2>
                    <p>Name: {$customerDetails['RecvFName']} {$customerDetails['RecvLName']} </p>
                    <p>Tel: {$customerDetails['Tel']}</p>
                    <p>Address: {$customerDetails['Address']}</p>
                  </div>";
        
        $invoiceQuery = mysqli_query($conn, "SELECT invoice.* , invoice_detail.*, product.*
                                           FROM invoice
                                           INNER JOIN invoice_detail ON invoice.InvID = invoice_detail.InvID
                                           INNER JOIN Product ON invoice_detail.ProID = Product.ProID
                                           WHERE invoice.CusID = '$customerId' AND invoice_detail.InvID = '$invoiceId'");
    
        $totalPriceAllItems = 0; 
        $stt = '';
        $detailsDisplayed = false;

        while ($row = mysqli_fetch_array($invoiceQuery)) {
            $totalPrice = $row['PricePerUnit'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;

            $stt = $row['Status'];
            
            if (!$detailsDisplayed) {
                echo "<div class='invoice-details'>
                        <p>Invoice #: {$row['InvID']}</p>
                        <p>Date: {$row['Period']}</p>
                    </div>";
                echo "</div>";
                
                echo "<table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>";

                $detailsDisplayed = true; // ตั้งค่า flag เป็นจริง เพื่อไม่ให้แสดงซ้ำ
            }

            echo "<tr>
                    <td>{$row['ProName']}</td>
                    <td>{$row['Qty']}</td>
                    <td>{$row['PricePerUnit']} ฿</td>
                    <td>$totalPrice</td>
                  </tr>";
        }

        echo "</table>";

        $tax = $totalPriceAllItems * 0.07;
        $totalAmount = $tax + $totalPriceAllItems;

        echo "<div class='invoice-total'>
                <p>SubTotal: $totalPriceAllItems ฿</p>
                <p>VAT: $tax ฿</p>
                <p>Discount: 0.00 ฿</p>
                <p>Total: $totalAmount ฿</p>
              </div>";

              echo "<div class='action-buttons'>";
              echo "<form class='action-button' action='genarateTemplate.php' method='post' target='_blank'>
                      <input type='hidden' name='id_invoice' value='".$invoiceId."'>
                      <input type='hidden' name='id_customer' value='". $customerId ."'>
                      <button type='submit'>
                          <img src='./image/print.png' alt='print'>
                      </button>
                    </form>";
              
              if ($stt == 'Unpaid') {
                  echo "<form class='action-button' method='post' action='accessOrder.php' target='go'>
                          <input type='hidden' name='id_invoice' value='".$invoiceId."'>  
                          <input type='hidden' name='id_customer' value='". $customerId ."'> 
                          <input class='buy-button' type='submit' value='ชำระเงิน'>           
                        </form>";
              }
              
        echo "</div>";

    }
    mysqli_close($conn);
?>
</body>
</html>
