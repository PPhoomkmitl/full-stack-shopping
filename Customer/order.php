<?php include('./component/session.php'); ?>
<?php include('../logFolder/AccessLog.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .order-container {
            max-width: 1150px;
            margin: 100px auto 50px auto;
            border: 2px solid #4CAF50;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            text-align: center;
            color: #4CAF50;
        }

        .order-details,
        .customer-details,
        .order-table,
        .order-total {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }


        .order-total {
            margin-left: auto;
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
            width: 150px;

        }

        h1,
        h2 {
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
        }

        .buy-button:hover {
            background-color: #2980b9;
        }

        .container_order {
            /* display: flex;
            flex-direction: row;
            justify-content: space-between; */
        }


        .item_order {
            width: 400px;
        }

        .item_order2 {
            align-self: flex-end;
            width: 280px;
            text-align: left;
        }

        center {
            margin-top: 100px;
        }

        #Status {
            font-weight: 800;
            font-size: large;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 Columns with equal width */
            grid-gap: 10px;
            /* Adjust the gap between columns */
        }

        .grid-item {
            border-right: 1.5px solid #ddd;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include('./component/accessNavbar.php') ?>
    <!-- <?php include('./component/backButton.php') ?> -->

    <?php
    include_once '../dbConfig.php'; 
    $uid = $_SESSION['id_username'];

    echo "<div class='order-container'>";
    if (isset($_SESSION['cart'])) {
        $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM customer WHERE customer.CusID = '$uid'");
        $customerDetails = mysqli_fetch_array($customerDetailsQuery);
        $customerId = $customerDetails['CusID'];
    } else {
        $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM customer INNER JOIN customer_account ON customer_account.CusID = customer.CusID WHERE customer.CusID = '$uid'");
        $customerDetails = mysqli_fetch_array($customerDetailsQuery);
        $customerId = $customerDetails['CusID'];
    }

    $customerDetailsQuery = mysqli_query($conn, "SELECT * FROM customer INNER JOIN customer_account ON customer_account.CusID = customer.CusID WHERE customer.CusID = '$uid'");
    $customerDetails = mysqli_fetch_array($customerDetailsQuery);

    
    $RecId = $_POST['id_order'];
    $payerQuery = mysqli_query($conn, "SELECT * FROM receive
        INNER JOIN payer ON receive.TaxID = payer.TaxID
        WHERE receive.RecID = '$RecId '");
    $payerResult = mysqli_fetch_array($payerQuery);


    $recevierQuery = mysqli_query($conn, "SELECT * FROM receive
        INNER JOIN receiver ON receive.RecvID = receiver.RecvID
        WHERE receive.RecID = '$RecId '");
    $recevierResult = mysqli_fetch_array($recevierQuery);


    $recQuery = mysqli_query($conn, "SELECT * FROM receive
    WHERE receive.RecID = '$RecId '");
    $recResult = mysqli_fetch_array($recQuery);


    echo "<div class='container_order'>";
    echo "<div  id='row-rev' class='invoice-container'>
        <div class='invoice-header'>
            <h1>เลขใบเสร็จของท่านคือ :{$recResult['RecID']} </h1>
        </div>";

    echo "<div class='item_order'>
                <h3>บริษัท </h3>
                <p>บริษัท: Fastwork ck🤔</p>
                <p>ที่ตั้ง: ประเทศลาดกระบัง</p>
                
        </div>";
    echo "<hr>";
    echo '<div class="grid-container">
            <div class="grid-item">';
    echo "<div class='item_order2'>
                    <p>ชื่อผู้จ่าย: {$payerResult['PayerFName']} {$payerResult['PayerLName']}</p>
                    <p>ที่อยู่จัดส่ง : {$payerResult['Tel']}</p>
                </div>";
    echo "</div>
                    <div class='grid-item'>
                        <div class='item_order2'>
                            <p id='Status'>สถานที่จัดส่ง</p>
                            <p>ชื่อผู้รับ : {$recevierResult['RecvFName']} {$recevierResult['RecvLName']}</p>
                            <p>ที่อยู่จัดส่ง : {$recevierResult['Address']}</p>
                            <p>เบอร์โทร : {$recevierResult['Tel']}</p>
                        </div>";

    echo "</div>
                    <div class='grid-item'>
                        <div class='item_order2'>
                            <p id='Status'>สถานะ : {$recResult['Status']}</p>
                            <p>วันที่สั่งซื้อ : {$recResult['OrderDate']}</p>
                            <p>วันที่ส่ง : {$recResult['DeliveryDate']}</p>
                        </div>
                    </div>
                </div>";


    echo "</div>";


    if (isset($_POST['id_order'])) {
        $customerId = $customerDetails['CusID'];
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
                echo "<h3>รายการสินค้าที่คุณซื้อ</h3>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>รายการสินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคา (บาท)</th>
                                <th>รวมทั้งหมด</th>
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
                <p>ราคารวม: $totalPriceAllItems ฿</p>
                <p>VAT: $tax ฿</p>
                <p>ส่วนลด: 0.00 ฿</p>
                <p>ยอดสุทธิ: $totalAmount ฿</p>
                <hr>
              </div>";

        echo "</div>";
    }
    mysqli_close($conn);
    ?>
</body>

</html>