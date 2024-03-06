<?php include('./component/session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    
    .flex-container {
        width: 100%;
        height: 200px;
        border: 1px solid #c3c3c3;
        display: flex;
    }
    #row-rev {
        flex-direction: row-reverse;
    }
    .flex-col {
        width: 100%;
        height: 50px;
    }
</style>
<body>
<?php
     include_once '../dbConfig.php'; 
     $uid = $_SESSION['id_username'];
    echo '<div  id="row-rev" class="invoice-container">
        <div class="invoice-header">
            <h1>เลขใบเสร็จของท่านคือ </h1>
        </div>
        <div class="flex-container">
            <div class="flex-col">
                a
            </div>
            <div class="flex-col">
                b
            </div>
            <div class="flex-col">
                c
            </div>
        </div>';
    if(isset($_POST['id_order'])){
        $customerId = $customerDetails['CusID'];
        $RecId = $_POST['id_order'];
        echo $RecId;
        $orderQuery = mysqli_query($conn, "SELECT Product.*, receive_detail.*  , receive.*
                    FROM receive_detail
                    INNER JOIN receive ON receive.RecID = receive_detail.RecID
                    INNER JOIN Product ON Product.ProID = receive_detail.ProID
                    WHERE receive_detail.RecID = '$RecId '");
                    
        $totalPriceAllItems = 0; 
        $detailsDisplayed = false; 

        while ($row = mysqli_fetch_array($orderQuery)) {
            $totalPrice = $row['PricePerUnit'] * $row['Qty'];
            $totalPriceAllItems += $totalPrice;

            if (!$detailsDisplayed) {
                echo "<div class='item_order2'>
                    <p id='Status'>Status : {$row['Status']}</p>
                    <p>Order #: {$row['RecID']}</p>
                    <p>Order Date: {$row['OrderDate']}</p>
                    <p>Delivery Date : {$row['DeliveryDate']}</p>
                    </div>
                </div>";
                
                echo "<table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>";

                $detailsDisplayed = true; 
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
 
        echo "<div class='order-total'>
                <p>SubTotal: $totalPriceAllItems ฿</p>
                <p>Tax: $tax ฿</p>
                <p>Discount: 0.00 ฿</p>
                <p>Total: $totalAmount ฿</p>
              </div>";  
        echo "</div>";
    }
    mysqli_close($conn);
?>
   
</div>

</body>
</html>
