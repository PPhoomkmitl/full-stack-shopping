<?php include('./component/session.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage</title>
    <link rel="icon" href="image/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100%;
        }

        .container h1 {
            font-size: 40px;
            color: black;
            font-weight: bold;
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
            padding: 3%;
            text-align: center;
            background-color: #fff;
            transition: transform 0.3s;
            height: 300px;
            max-width: 200px;
            display: flex;
            flex-direction: column;
            /* Set a fixed height for the cards */
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image-container {
            max-height: 200px;
            /* Set a maximum height for the image container */
            overflow: hidden;
            /* Hide overflowing content */
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* Maintain aspect ratio and cover the entire container */
            margin-bottom: 10px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            margin-top: auto;
            /* Push the product-info to the bottom */
        }

        .product-name {
            color: black;
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

    

        .main-about-us-container {
            margin-top: 80px;
        }

        .main-about-us-container h1 {
            margin-bottom: 40px;
        }

        .Slide-Container {
            width: 100%;
            max-width: 100%;
            height: 800px;
            margin-top: 3%;
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
            color: #fff;
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
            border: 1px solid #333;
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

    <div class="navCon">
        <?php include('./component/accessNavbar.php'); ?>
    </div>

    <div class="Slide-Container">
        <a2 href="#">
            <img src="./image/clothes.jpg">
            <div class="overlay">
                <h1 class="overlay-text">second hand clothes</h1>
                <h3 class="overlay-text">Sell ​​second hand clothes / shoes from all over the world.</h3>

            </div>
        </a2>
    </div>
    <div class="container">
        <h1>New Arrival</h1>
        <div class="product-container">
            <?php
            $cur = "SELECT * FROM product";
            $msresults = mysqli_query($conn, $cur);

            if (mysqli_num_rows($msresults) > 0) {
                while ($row = mysqli_fetch_assoc($msresults)) {
                    echo "<div class='product-card'>
                            <div class='product-image-container'>
                                <img class='product-image' src='data:image/*;base64," . base64_encode($row['ImageData']) . "'>
                            </div>
                            <div class='product-info'>
                                <p class='product-name'>{$row['ProName']}</p>
                                <p class='product-price'>Price {$row['PricePerUnit']} ฿</p>
                                <form method='post' action='detailProduct.php'>
                                    <input type='hidden' name='id_product' value='{$row['ProID']}'>
                                    <input class='buy-button' type='submit' value='Click'>
                                </form>
                            </div>
                    </div>
                    ";
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
                            <h3>Reduce your expenses from buying expensive products every year to buy second-hand products that are still in demand in this era.​​</h3>
                            <h3>เสื้อผ้า/ รองเท้า มือสอง 
                                สภาพดี เกรด A
                                ปี 70s - 90s​</h3>
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
        overflow: hidden;
    }

    .about-us-image {
        flex: 1;
        margin-right: 5%;
        margin-left: 10%;
        max-width: 100%;
        /* Ensure the image doesn't exceed its container */
        height: 100%;
        overflow: hidden;
    }

    .about-us-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Maintain aspect ratio and cover the entire container */
    }

    .about-us-details {
        flex: 1.8;
        text-align: left;
        flex-direction: column;
        color: #333;

    }

    .about-us-details h2 {
        color: #333;
    }


    .about-us-details-body {
        margin-top: 10%;
        width: 700px;

    }

    .about-us-details-body h2 {
        font-weight: bold;

    }

    .about-us-details-body h3 {
        font-weight: inherit;

    }
</style>

</html>