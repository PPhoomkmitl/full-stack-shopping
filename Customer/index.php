<?php include('./component/session.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            margin-top: 70px;
            text-align: center;
        }

        .product-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
        }

        .product-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex: 0 1 calc(25% - 20px);
            padding: 30px 10px;
            text-align: center;
            background-color: #fff;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            width: 300px;
            height: 280px;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            color: #27ae60;
            margin-bottom: 15px;
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

        /* .product-container {
            width: 80%;
         
            margin: 0 auto;
    
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
     
        } */

        /* .product-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex: 0 1 calc(18% - 20px);
            padding: 80px;
            text-align: center;
            background-color: #fff;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            width: 200px;
            height: 200px;
            margin-bottom: 10px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-price {
            color: #27ae60;
            margin-bottom: 15px;
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
        } */

        .main-about-us-container {
            margin-top: 80px;
        }

        .main-about-us-container h1 {
            margin-bottom: 40px;
        }

        .Slide-Container {
            width: 100%;
            max-width: 100%;
            height: 500px;
            margin-top: 109px;
            overflow: hidden;
            position: relative;
        }

        .Slide-Container a2 {
            display: block;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .Slide-Container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
        }

        .overlay-text {
            height: auto;
            width: auto;
            font-weight: bold;
            /* Add any additional styling for the text */
        }

        .overlay-text h3 {
            height: auto;
            width: auto;
            font-weight: lighter;
            /* Add any additional styling for the text */
        }

        #slideshow {
            width: 100%;
            overflow: hidden;
            left: 0;
            right: 0;
        }

        #slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            box-sizing: border-box;
        }

        /* ถ้าต้องการให้รูปภาพเต็มจอแนะนำให้ใช้ width: 100vw; และ height: 100vh; */
        .slide img {
            width: 100%;
            height: 100%;
            /* ปรับเป็น 100% ของความสูงของ .Slide-Container */
            object-fit: cover;
            /* ป้องกันการเอียงภาพและตัดเรียงซ้อน */
        }


        .navCon {
            z-index: 100;
            margin-bottom: 10%;
            /* border: 1px solid #333; */
        }

        .footer {
            height: 200px;
            background-color: rgb(25, 135, 84);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            /* Set flex direction to column */
        }
    </style>
</head>

<body>

    <?php include('./component/getFunction/getProductImages.php'); ?>

    <div class"navCon">
        <?php include('./component/accessNavbar.php'); ?>
        <?php include_once '../dbConfig.php';  ?>
    </div>

    <div class="Slide-Container">
        <a2 href="#">
            <img src="./image/clothes.jpg">
            <div class="overlay">
                <h1 class="overlay-text">เสื้อผ้ามือสอง</h1>
                <h3 class="overlay-text">ขายเสื้อผ้า / รองเท้า มือสอง​ จากทั่วทุกมุมโลก</h3>

            </div>
        </a2>
    </div>
    <div class="container">
        <h1>Related Products</h1>
        <div class="product-container">
            <?php
            $cur = "SELECT * FROM product";
            $msresults = mysqli_query($conn, $cur);

            if (mysqli_num_rows($msresults) > 0) {
                while ($row = mysqli_fetch_assoc($msresults)) {
                    echo "<div class='product-card'>
                            <img class='product-image' src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                            <p class='product-name'>{$row['ProName']}</p>
                            <p class='product-price'>ราคา {$row['PricePerUnit']}</p>
                            <form method='post' action='detailProduct.php'>
                                <input type='hidden' name='id_product' value='{$row['ProID']}'>
                                <input class='buy-button' type='submit' value='ซื้อสินค้า'>
                            </form>
                        </div>";
                }
            } else {
                echo "<center><h1>ไม่มีสินค้า</h1></center>";
            }
            ?>
        </div>
        <!-- About Us Template -->
        <div class="main-about-us-container">
            <section id="about">
                <h1>About Us</h1>
                <div class="about-us-container">
                    <div class="about-us-image">
                        <img src="./image/jeans.jpg" alt="About Us Image">
                    </div>
                    <div class="about-us-details">
                        <div class="about-us-details-body">
                            <h2>Why Puma Fast-Shirt​</h2>
                            <h2>ลดค่าใช้จ่ายของคุณจากการซื้อสินค้าแพงๆทุกปีมาซื้อเป็นสินค้ามือสองที่ยังคงเป็นที่ต้องการในยุคนี้​</h2>
                            <h2>เสื้อผ้า/ รองเท้า มือสอง
                                สภาพดี เกรด A
                                ปี 70s - 90s​</h2>
                        </div>
                        <!-- Add more content as needed -->
                    </div>
                </div>
                <div>
            </section>
            <!-- Contact Template -->
            <section id="footer">
                <div class="footer">
                    <h2>Contact</h2>
                    <p>For any inquiries or assistance, feel free to contact us:</p>
                    <p>Email: pumaFastWork@co.th</p>
                    <p>Phone: +66 88 101 9863</p>
                    <!-- Add more content as needed -->
                </div>
            </section>
        </div>
</body>

<style>
    /* New styles for About Us template */
    .about-us-container {
        display: flex;
        margin: 0px 100px;
        background-color: rgba(94, 137, 120, 0.5);
        max-height: 300px;
        margin-bottom: 80px;
        padding: 10px 0px;
        border: #5E8978 solid 1px;
        border-radius: 10px;
    }

    .about-us-image {
        flex: 1;
        margin-right: 5%;
        margin-left: 10%;
    }

    .about-us-details {
        flex: 1.8;
        text-align: left;
        flex-direction: column;

    }


    .about-us-details-body {
        width: 700px;

    }

    .about-us-image img {
        width: 100%;
        height: 100%;
        /* Optional: Add rounded corners to the image */
    }
</style>

</html>