<?php include('./component/session.php'); ?>
<?php include('../logFolder/AccessLog.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }


        .container-body {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 100px auto 0px auto;
            padding: 20px;
        }

        .container-1 {
            width: 60%;
            display: flex;
            flex-wrap: wrap;
        }

        .product-image {
            flex: 0 0 calc(50% - 35px);
            margin: 5px;
            box-sizing: border-box;
        }

        .product-image img {
            width: 300px;
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;

        }

        .container-2 {
            width: 40%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
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

        .button-increase {
            border: 1px solid #f5f6f6;
            padding: 4px;
            display: inline-block;
            border-radius: 3px;
        }

        #amount {
            border: none;
            outline: none;
            background: none;
            text-align: center;
            width: 50px;
            height: 25px;
        }

        #change-amount {
            background-color: #f3f6f9;
            border: 1px solid #f5f6f6;
            width: 1.4em;
            border-radius: .25rem;
            font-weight: 300;
            font-size: 1.4em;
            color: #878a99;
        }

        #change-amount:hover {
            background-color: #f3f0f0;
        }

        #change-amount.clicked {
            background-color: #030000;
        }

        p {
            margin: 0;
            padding-bottom: 8%;
            font-size: 50px;
        }

        a {
            text-decoration: none;
            display: inline-block;
            color: #ef476f;
            /* เปลี่ยนสีข้อความลิงค์ */
        }

        a.aBack {
            margin-top: 3%;
            margin-left: 3%;
            display: inline-block;
            padding: 10px 20px;
            background-color: #ef476f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        a.aBack:hover {
            background-color: #e0476f;
        }

        /*
        ===================
            Overlay Zone
        ===================
          */

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .close-button {
            background-color: #3498db;
            color: #fff;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        .close-button:hover {
            background-color: #2980b9;
        }

        .navCon {
            z-index: 100;
            border: 1px solid #333;
        }
    </style>
</head>
<script>
    function incrementAmount() {
        var amountInput = document.getElementById('amount');
        amountInput.value = parseInt(amountInput.value) + 1;
    }

    function decrementAmount() {
        var amountInput = document.getElementById('amount');
        if (parseInt(amountInput.value) > 1) {
            amountInput.value = parseInt(amountInput.value) - 1;
        }
    }
</script>

<body class="detail-page">
    <script>
        function openOverlay() {
            document.getElementById('overlay').style.display = 'flex';
            // Prevent the form from submitting immediately
            return false;
        }

        function closeOverlay() {
            // Perform the redirection after closing the overlay
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</body>

</html>
<div class="navCon">
    <?php include('./component/accessNavBar.php'); ?>
</div>
<?php include('./component/getFunction/getProductImages.php');?>

<?php
include_once '../dbConfig.php';
$code = $_POST['id_product'];
$cur = "SELECT * FROM product WHERE ProID = $code ";
$msresults = mysqli_query($conn, $cur);
$row = mysqli_fetch_array($msresults);
$onHandStock = $row['StockQty'] - $row['OnHands'];
echo " <div class='container-body'>
            <div class='container-1'>
                <div class='product-image'>
                    <img src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                </div>
                <div class='product-image'>
                    <img src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                </div>
                <div class='product-image'>
                    <img src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                </div>
                <div class='product-image'>
                    <img src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                </div>
            </div>
            <div class='container-2'>
                <p><strong>{$row['ProName']}</strong></p>
                <p style='font-size:20px;'>detail product</p>
                <p style='font-size:20px;'>
                    {$row['Description']}
                </p>
                <p style='font-size:30px;'>Price: {$row['PricePerUnit']} ฿</p>
                <p style='font-size:20px; color:grey;'>in stock: {$onHandStock}</p>
                <form method='post' action='accessCart.php' classname='buy-button'>
                    <input type='hidden' name='id_product' value='{$row['ProID']}'>  
                    <div class='button-increase'>
                        <button type='button' id='change-amount' onclick='decrementAmount()'>-</button>    
                        <input type='text' name='amount' id='amount' value='1' readonly>
                        <button type='button'  id='change-amount' onclick='incrementAmount()'>+</button>
                    </div>
                    <br><br>
                    ";
if ($onHandStock <= 0) {
    echo "<input class='buy-button' type='submit' style='background-color:gray;' name='add_to_cart' value='out of stock' disabled>";
} else {
    echo "<input class='buy-button' type='submit' name='add_to_cart' value='add to cart'>";
}
echo "         
                </form>
            </div>
        </div>";
?>

<div id="overlay" class="overlay" style="display: none;">
    <div class="modal">
        <p>เพิ่มลงตะกร้าเรียบร้อย!</p>
        <button class="close-button" onclick="closeOverlay()">ปิด</button>
    </div>
</div>