<?php 
include('./component/session.php');
include('../logFolder/AccessLog.php');
include('../logFolder/CallLog.php');
include('./component/getFunction/getName.php'); ?>
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
            margin-bottom: 1rem;
        }

        Textarea {
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
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

        input[type="submit"] {
            background-color: #488978;
            font-weight: bold;
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #5E8978;
        }

        input[type="submit"]:focus {
            background-color: #5E8978;
        }

        .breadcrumb {
            background-color: #f8f9fa; /* Set background color */
            padding: 10px; /* Add padding for better visual */
            border-radius: 5px; /* Add border radius for rounded corners */
        }

        .breadcrumb-item {
            font-size: 18px; /* Adjust font size */
        }

        .breadcrumb-item a {
            color: #007bff; /* Set link color */
            text-decoration: none; /* Remove default link underline */
        }

        .breadcrumb-item.active {
            color: #000; /* Set color for active/last item */
        }
    </style>
</head>

<body>
    <div class="mx-5">
        <?php include('./component/backButton.php'); ?>
    </div>
    <form id="profileForm" method="post" action="accessInvoice.php">
        <?php
        if (isset($_SESSION['id_username'])) {
            $uid = $_SESSION['id_username'];

            include_once '../dbConfig.php'; 
            $query_address = "SELECT * FROM receiver 
            INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
            WHERE receiver_detail.CusID = '$uid'";
            $result_address = mysqli_query($conn, $query_address);
            if (mysqli_num_rows($result_address) > 0) {
                // Fetch a single row from the result set
                $row = mysqli_fetch_assoc($result_address);
            }
        }
        ?>
        <div class="checkout-container">
            <div class="checkout-header">
                <h2>Checkout</h2>
            </div>

            <div class="checkout-steps">
                <div class="checkout-step active">Step 1: Shipping</div>
                <div class="checkout-step">Step 2: Payment</div>
                <div class="checkout-step">Step 3: Success</div>
            </div>


            <div class="container">
            <div class="col-md-8 order-md-1 mx-auto py-4">
            <h4 class="mb-3">Billing address</h4>
            <form class="needs-validation" novalidate="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                      
                        <label for="fullname">First Name</label>
                        <input class="form-control" type="text" id="fullname" name="fname" value="<?php echo $row['RecvFName'] ?? ''; ?>" required>              
                        <div class="invalid-feedback"> Valid first name is required. </div>
                    </div>
                    <div class="col-md-6 mb-3">
                   
                        <label for="lastname">Last Name</label>
                        <input class="form-control" type="text" id="lastname" name="lname" value="<?php echo $row['RecvLName'] ?? ''; ?>"required>
                        <div class="invalid-feedback"> Valid last name is required. </div>
                    </div>
                </div>
             
                <div class="mb-3">
                    <label for="tel">Tel<span>*</span></label>
                    <input class="form-control" required type="tel" name="tel" value="<?php echo $row['Tel'] ?? ''; ?>">
                    <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                </div>
                <div class="mb-3">
                    <label for="address">Address</label>
                    <textarea style="resize:none;" name="address" id="address" rows="3" required><?php echo $row['Address'] ?? ''; ?></textarea>
                    <div class="invalid-feedback"> Please enter your shipping address. </div>
                </div>

                    <input type='submit'>

                    <!-- ตรวจสอบว่าเป็น Guest หรือ User และแสดงปุ่ม 'ชำระเงิน' ตามเงื่อนไข -->
                    <?php if (isset($_SESSION['cart'])): ?>
                        <input type='hidden' name='cart' value='<?php echo json_encode($_SESSION['cart']); ?>'>
                    <?php elseif (isset($_SESSION['id_username'])): ?>
                        <input type='hidden' name='id_customer' value='<?php echo $uid; ?>'>
                    <?php else: ?>
                        <p>Oops Something went wrong</p>
                        <?php echo 'header("Location: ./cart.php")'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
        

    <!-- <script> -->

        <!-- // function submit() {
        //     document.querySelector('form').submit();
        // } -->
    <!-- </script> -->
</body>

</html>