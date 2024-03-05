<?php include('./component/session.php');

/* มี ฺBug ถ้าไม่ลบ Guest ก่อนที่จะกดกลับไปหน้าหลัก */
/* --------------------------------------- */

include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .checkout-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            bottom: 0;
        }

        .flex-container {
            width: 100%;
            display: flex;
        }

        .checkout-sidebar {
            flex:0.5;
            border: 1px solid #ddd;
            padding: 20px;
            position: sticky;
            bottom: 0;
            border-color: #000;
            width: max-content;
            height: max-content;
        }



        .checkout-content {
            flex: 2;
            padding: 20px;
        }

        .checkout-header {
            background-color: #488978;
            color: #fff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .checkout-steps {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .checkout-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            cursor: pointer;
        }

        .checkout-step.active {
            border-bottom: 2px solid #27ae60;
        }

        .checkout-step:not(.active) {
            color: #888;
        }

        .checkout-form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .checkout-button {
            background-color: #27ae60;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .checkout-button:hover {
            background-color: #219653;
        }
        .checkout-sidebar {
            flex: 0.5;
            border: 1px solid #ddd;
            padding: 20px;
            position: sticky;
            bottom: 0;
            border-color: #000;
            width: max-content;
            height: max-content;
            background-color: #f9f9f9; /* สีพื้นหลังของ sidebar */
            border-radius: 8px; /* เพิ่มมุมโค้งให้กับ sidebar */
        }

        .invoice-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

    /* .invoice-header {
        color: #fff;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    } */

        .customer-details,
        .summary-details {
            margin-bottom: 20px;
        }

        .text-container {
            margin-bottom: 10px;
        }

        .view-details-link {
            color: #3498db;
            text-decoration: none;
        }

        .view-details-link:hover {
            text-decoration: underline;
        }
        .customer-details h4,
        .summary-details h4 {
            background-color: #3498db; /* สีพื้นหลังของ <h4> */
            color: #fff; /* สีข้อความของ <h4> */
            padding: 10px; /* ระยะห่างขอบของ <h4> */
            border-radius: 8px; /* มุมโค้งของ <h4> */
            margin-top: 0; /* ลบ margin ด้านบนของ <h4> */
        }
        input[type="submit"]{
            background-color:#488978;
            font-weight:bold;
            color:white;
        }
        input[type="submit"]:hover{
            background-color: #5E8978;
        }
        input[type="submit"]:focus{
            background-color: #488978;
        }
        .backButton {
            margin-left: 90px;
        }

    </style>
</head>

<body>

    <div class="backButton">
        <?php include('./component/backLogIn.php'); ?>
    </div>
    <!-------------------------------------------------------------------->
    <form id="profileForm" method="post" action="accessOrder.php">
        <?php
        if (isset($_SESSION['id_username'])) {
            $uid = $_SESSION['id_username'];
            $inv = $_POST['id_invoice'];
            $recv_id = $_POST['id_receiver'];


            $cx =  mysqli_connect("localhost", "root", "", "shopping");
            $query_address = "SELECT * FROM receiver 
            INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
            WHERE receiver_detail.CusID = '$uid'";
            $result_address = mysqli_query($cx, $query_address);
            if (mysqli_num_rows($result_address) > 0) {
                // Fetch a single row from the result set
                $row = mysqli_fetch_assoc($result_address);
            }
        }
        ?>
        <div class="checkout-container">
            <div class="checkout-header">
                <h4>Checkout</h4>
            </div>

            <div class="checkout-steps">
                <div class="checkout-step">Step 1: Shipping</div>
                <div class="checkout-step active">Step 2: Payment</div>
                <div class="checkout-step">Step 3: Success</div>
            </div>

            <!-------------------------------------------------------------->
            <div class="flex-container">
                <!-- Main content -->
                <div class="checkout-content">
                    <div id="paymentForm" class="checkout-form" style="display: block;">
                        <!-- Payment form content -->
                        <div class="form-group">
                            <label for="firstname">Reciever First Name: </label>
                            <input type="text" id="fname" name="fname" value="<?php echo $row['RecvFName'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="lasttname">Reciever Last Name: </label>
                            <input type="text" id="lname" name="lname" value="<?php echo $row['RecvLName'] ?? ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tel">Tel :</label>
                            <input type="text" id="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>" required>
                        </div>
                        <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                        <input type='hidden' name='id_receiver' value='<?php echo $row['RecvID']; ?>'>
                        <input type='hidden' name='id_invoice' value='<?php echo $inv; ?>'>
                        <!-- <button class="checkout-button" onclick="submit()">ชำระเงิน</button> -->
                        <input type="submit">
                    </div>
                </div>

                    <!-- Sidebar content -->
                    <?php
                    $cx = mysqli_connect("localhost", "root", "", "shopping");


                    if (isset($_POST['id_invoice'])) {
                        $customerDetailsQuery = mysqli_query($cx, "SELECT * FROM receiver 
                        INNER JOIN receiver_detail ON receiver.RecvID =  receiver_detail.RecvID WHERE receiver_detail.CusID = '$uid' AND receiver_detail.RecvID = '$recv_id'");
                        $customerDetails = mysqli_fetch_array($customerDetailsQuery);
                        $customerId = $uid;
                        $invoiceId = $_POST['id_invoice'];

                        echo '<div class="col-md-4 order-md-2 mb-4">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted ">Delivery Summary</span>
                            </h4>
                            <ul class="list-group mb-3 sticky-top">
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div class="text-success">
                                        <h6 class="my-0 bg-light ">Shipping Address</h6>
                                        <small class="text-muted">Name: '.$customerDetails['RecvFName'] . ' ' . $customerDetails['RecvLName'] .'</small><br>
                                        <small class="text-muted">Tel: '.$customerDetails['Tel'].'</small><br>
                                        <small class="text-muted">Address: '. $customerDetails['Address'] .'</small>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>';
                                        $totalPriceAllItems = 0;
                                        $invoiceDetailsQuery = mysqli_query($cx, "SELECT * , product.ProName FROM invoice_detail
                                        INNER JOIN product ON product.ProID =  invoice_detail.ProID 
                                        INNER JOIN invoice ON invoice.InvID =  invoice_detail.InvID 
                                        WHERE invoice.InvID  = '$inv'");

                                        echo '<h6 class="my-0 text-success">Order summary</h6>';
                                        while ($invoiceDetails = mysqli_fetch_array($invoiceDetailsQuery)) {
                                            $totalPrice = $invoiceDetails['PricePerUnit'] * $invoiceDetails['Qty'];
                                            $totalPriceAllItems += $totalPrice;

                                            echo '   
                                                <small class="text-muted">Name: '.$invoiceDetails['ProName'] . ' ' . $customerDetails['RecvLName'] .'</small><br>
                                                <small class="text-muted">Quantity: '.$invoiceDetails['Qty'].' piece</small><br>
                                                <small class="text-muted">Price: '.$invoiceDetails['PricePerUnit'].' ฿ </small><br>
                                                <hr style="width:370px;">
                                            ';                         
                                        }
                                        $tax = $totalPriceAllItems * 0.07;
                                        $totalAmount = $tax + $totalPriceAllItems;
                                    echo '
                                    </div>


                         
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed bg-light">
                                    <div>                          
                                        <small class="text-muted">SubTotal</small><br>
                                        <small class="text-muted">VAT</small><br>
                                        <span>Total (BATH)</span>
                                       
                                    </div>
                                    <div>
                                    <span class="text-muted">'.$tax.' ฿</span><br>
                                    <span class="text-muted">'.$totalPriceAllItems.' ฿</span><br>
                                    <span class="text-muted">'.$totalAmount.' ฿</span>
                                    </div>
                                    
                                </li>                                          
                            </ul>
                        </div>';
                    }
                    ?>
                    <!-- <a href='./invoice.php?id_invoice={$inv}&id_receiver={$row['RecvID']}' class='view-details-link' style='text-decoration: underline; color: #3498db;'>View Full Details</a> -->
                </div>
            </div>
        </div>
        </div>
        </div>
    </form>

</body>

</html>

    <script>
        // function submit() {
        //     document.querySelector('form').submit();
        // }
    </script>