<?php include('./component/session.php');
include_once '../dbConfig.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+WyXYEy4NotmFXFgAj4DQISO2aHkwKbofjM" crossorigin="anonymous">
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
            padding-bottom: 10px;
            
        }

        .checkout-header {
            background-color: #488978;
            color: #ffff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .checkout-header h2 {
            color: #ffff;
            margin-left: 20px;
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
            display: none;
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

        .order-container {
            max-width: 1110px;
            margin: 5px auto 50px auto;
            border: 1.5px solid rgba(0, 0, 0, .125);
            background-color: #fff;
            background-clip: border-box;
            padding: 20px;
            border-radius: .25rem;
            /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); */
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

        .action-buttons {
            display: flex;
            justify-content: space-between;

        }

        .action-buttons h1 {
            margin-top: 0px;
            margin-bottom: 10px;

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

        .dot {
            height: 40px;
            width: 40px;
            /* margin-left: 5px;
            margin-right: 3px; */
            margin-top: 0px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #9BA4AB;

        }


        .order-date {
            color: #999;
        }

        .connecting-line {
            flex-grow: 1;
            height: 3px;
            background-color: #488978;
        }

        /* Define different colors for different statuses */
        .dot.confirm {
            background-color: #488978;
        }

        .dot.pending {
            background-color: #488978;
        }

        .dot.inprogress {
            background-color: #488978;
        }

        .dot.delivered {
            background-color: #488978;
        }

        .backButton {
            margin-left: 90px;
        }

        .product-image {
            width: 40px;
            height: 40px;
        }

        .navBar {
            margin-top: -20px;
        }
    </style>
</head>


<body>
    <div class="navBar">
        <?php include('./component/checkoutNavbar.php'); ?>
    <div>
    <?php
    if (isset($_SESSION['id_username'])) {
        $cusID = $_SESSION['id_username'];
        $orderID = $_POST['id_order'];

        $query_address = "SELECT * FROM shipping_address WHERE shipping_address.CusID = $cusID";
        $result_address = mysqli_query($conn, $query_address);
        if (mysqli_num_rows($result_address) > 0) {
            $row = mysqli_fetch_assoc($result_address);
        }
    }

    $billingQuery = mysqli_query($conn, "SELECT * FROM orders
                    INNER JOIN billing_address ON orders.billing_address_id = billing_address.address_id
                    WHERE orders.order_id = $orderID");
    $billingResult = mysqli_fetch_array($billingQuery);


    $shippingQuery = mysqli_query($conn, "SELECT * FROM orders
                    INNER JOIN shipping_address ON orders.shipping_address_id = shipping_address.address_id
                    WHERE orders.order_id = '$orderID '");
    $shippingResult = mysqli_fetch_array($shippingQuery);


    $orderQuery = mysqli_query($conn, "SELECT * FROM orders
                INNER JOIN customer ON customer.CusID = orders.CusID
                WHERE orders.order_id = '$orderID '");
    $orderResult = mysqli_fetch_array($orderQuery);
    ?>

    <!----------------------------------------Check out Header------------------------------------------------->
    <div class="checkout-container">
        <div class="checkout-header">
            <h2>Checkout</h2>
        </div>

        <div class="checkout-steps">
            <div class="checkout-step">Step 1: Shipping</div>
            <div class="checkout-step active">Step 2: Success</div>
        </div>

        <div id="successForm" class="checkout-form" style="display: block; margin-left:50px;">
            <?php
                if($orderResult['fullfill_status'] == 'Unfulfilled' && $orderResult['shipping_status'] != 'Canceled') {
                    echo '<h3>Order waiting for confirmation</h3>';
                    echo '<p>Your order has not been confirmed. Thank you for shopping with us.</p>';
                } else if($orderResult['fullfill_status'] == 'Fulfilled' && $orderResult['shipping_status'] != 'Canceled'){
                    echo '<h3>Your Order has been placed </h3>';
                    echo '<p>Your order has been confirmed. Thank you for shopping with us.</p>';
                } else {
                    echo '<h3>Your Order has been canceled </h3>';
                }
            ?>
        </div>
        <!----------------------------------------Order Tracking------------------------------------------------->
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center ">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="lead fw-normal">Order Tracking</span>
                                    <span class="text-muted small">by DHFL on <?php echo $orderResult['order_date']; ?></span>
                                </div>
                            </div>
                            <hr class="my-4">

                            <div class="d-flex flex-row justify-content-between align-items-center">
                                <?php
                                // Define colors for different order statuses
                                $statusColors = array(
                                    'Pending' => 'pending',
                                    'Inprogress' => 'inprogress',
                                    'Delivered' => 'delivered'
                                );

                                $delivered = false;

                                // Loop through each status and display corresponding dot
                                foreach ($statusColors as $status => $color) {
                                    echo '<div class="d-flex flex-column align-items-center">';
                                    if ($status === $orderResult['shipping_status']) {
                                        if ($status === 'Pending') {
                                            echo '<div class="dot ' . $color . '"><i class="fas fa-clock text-white" ></i></div>';
                                        } else if ($status === 'Inprogress') {
                                            echo '<div class="dot inprogress"><i class="fas fa-spinner text-white"></i></div>';
                                        } else {
                                            echo '<div class="dot delivered"><i class="fas fa-check-circle text-white"></i></div>';
                                        }

                                        echo '<span class="order-date">' . substr($orderResult['order_date'], 0, 10) . '</span><span>' . $status . '</span></div>';
                                    }  else if ($status === 'Pending' && in_array($orderResult['shipping_status'], ['Pending', 'Inprogress', 'Delivered'])) {
                                        // Change color for Pending status
                                        echo '<div class="dot pending"><i class="fas fa-clock text-white"></i></div>';
                                        echo '<span class="order-date">' . substr($orderResult['order_date'], 0, 10) . '</span><span>' . $status . '</span></div>';
                                    } else if ($status === 'Inprogress' && in_array($orderResult['shipping_status'], ['Inprogress', 'Delivered'])) {
                                        // Change color for Inprogress status
                                        echo '<div class="dot inprogress"><i class="fas fa-spinner text-white"></i></div>';

                                        echo '<span class="order-date">' . substr($orderResult['delivery_date'], 0, 10) . '</span><span>' . $status . '</span></div>';
                                    } else {
                                        echo '<div class="dot"></div>';
                                        echo '<span class="order-date">' . substr($orderResult['delivery_date'], 0, 10) . '</span><span>' . $status . '</span></div>';
                                    }


                                    if ($status === 'Delivered') {
                                        $delivered = true;
                                    }
                                    // Add connecting line for all statuses except the first one
                                    if ($delivered === false) {
                                        echo '<div class="connecting-line"></div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





        <!-- Bootstrap JS and Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!------------------------------------------------------------------------------------------>
        <?php
        echo '<section class="h-100 gradient-custom">
        <div class="container py-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px;">
                    <div class="card-header px-4 py-5 d-flex justify-content-between" >
                        <h5 class="text-muted mb-0">Thanks for your Order,<span style="color: #488978;"> ' . $orderResult['CusFName'] . '!</span></h5>
                        <div class="action-buttons">';
                        if($orderResult['invoice_id'] !== null && $orderResult['fullfill_status'] == 'Fulfilled' && $orderResult['shipping_status'] != 'Canceled'){          
                           echo '<form class="action-button" action="pdf.php" method="post" target="_blank" style="display: inline-block;">
                                <input type="hidden" name="order_id" value="' . $orderID . '">
                                <input type="hidden" name="id_customer" value="' . $cusID . '">
                                <button type="submit">
                                    <img src="./image/print.png" alt="print">
                                </button>
                            </form>';
                        }
                        echo '</div>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <p class="lead fw-normal mb-0" style="color: #488978;">Receipt</p>
                        </div>';


        // <?---------        ส่วนของ detail     -------->
        if (isset($_POST['id_order'])) {

            $orderQuery = mysqli_query($conn, "SELECT product.*, order_details.* , orders.* 
                                        FROM order_details
                                        INNER JOIN orders ON orders.order_id = order_details.order_id
                                        INNER JOIN product ON product.ProID = order_details.ProID            
                                        WHERE orders.order_id = $orderID");

            $totalPriceAllItems = 0;
            $detailsDisplayed = false;


            while ($row = mysqli_fetch_array($orderQuery)) {
                $totalPrice = $row['PricePerUnit'] * $row['quantity'];
                $totalPriceAllItems += $totalPrice;

                echo '<div class="card shadow-0 border mb-4">
                <div class="card-body">
                    <div class="row">       
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center"> 
                            <img class="product-image" src="data:image/*;base64,' . base64_encode($row['ImageData']) . '">
                        </div>
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0">' . $row['ProName'] . '</p>
                        </div>
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">Qty:' . $row['quantity'] . '</p>
                        </div>
                        <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">price: ' . $row['PricePerUnit'] . '</p>
                        </div>
                        <div class="col-md-3 text-center d-flex justify-content-center align-items-center">
                            <p class="text-muted mb-0 small">' . $totalPrice . '</p>
                        </div>
                    </div>
                    <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                    <div class="row d-flex align-items-center"></div>
                </div>';
            }
        }

        echo "</table>";
        $tax = $totalPriceAllItems * 0.07;
        $totalAmount = $tax + $totalPriceAllItems;





        echo '<div class="d-flex justify-content-between pt-2 pb-2">
                    <p class="fw-bold mb-1 mx-3">Order Details</p>
                    <p class="text-muted mb-0" style="font-size: 1.2rem;">
                        <span class="fw-bold" style="margin-right: 17px;">Total</span>
                        <span class="fw-bold mx-5"> ' . $totalPriceAllItems . '฿</span>
                    </p>
                </div>';

        echo '<div class="d-flex justify-content-between mb-5" style="margin-top: -10px;">
                    <div class="flex-col mb-5">
                        <p class="text-muted mb-0 mx-3">Receipt: <span style="font-weight: bold;">' . $orderResult['order_id'] . '</span></p>
                        <p class="text-muted mb-0 mx-3">Order Date: ' . $orderResult['order_date'] . '</p>
                    </div>
                    <div class="flex-col mb-5">
                        <p class="text-muted mb-0" style="font-size: 1rem;"><span class="fw-bold me-4">VAT 7%</span><span class="fw-bold mx-5">' . $tax . '฿</span></p>
                        <p class="text-muted mb-0" style="font-size: 1rem;"><span class="fw-bold me-4">Discount</span><span class="fw-bold mx-5">0.00฿</span></p>
                        <p class="text-muted mb-0" style="font-size: 1rem;"><span class="fw-bold me-4">Delivery Charges</span> Free</p>
                    </div>
                </div>';




        echo   '<div class="card-footer border-0 px-4 py-5"
                        style="background-color: #488978; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                        <h5 class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">Total
                        paid: <span class="h2 mb-0 ms-2">' . $totalAmount . ' ฿</span></h5>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </section>';
        // if (isset($_SESSION['guest'])) {
        //     // Unset session ที่คุณต้องการ
        //     unset($_SESSION['guest']);
        //     unset($_SESSION['id_username']);
        // }
        mysqli_close($conn);
        ?>
    </div>
    <script>
        function getFormId(step) {
            // Map step number to form ID
            return (step === 1) ? 'shippingForm' :
                (step === 2) ? 'paymentForm' :
                (step === 3) ? 'successForm' :
                '';
        }
    </script>
</body>

</html>