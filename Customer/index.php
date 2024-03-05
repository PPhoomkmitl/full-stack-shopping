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

        .product-container {
            width: 100%;
            max-width: 1550px;
            display: flex;
            flex-wrap: wrap;
            margin-top: 5%;
        }

        .product-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex: 0 1 calc(18% - 20px);
            padding: 10px;
            text-align: center;
            background-color: #fff;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-image {
            width: 100px;
            height: 100px;
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

        .Slide-Container {
            width: 100%;
            max-width: 100%;
            height: 500px;
            /* ปรับความสูงตามที่ต้องการ */
            margin-top: 125px;
            /* border: 1px solid #333; */
            overflow: hidden;
            z-index: -100;
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
    </style>
</head>

<body>
    <?php include('./component/session.php'); ?>
    <div class"navCon">
        <?php include('./component/accessNavbar.php'); ?>
    </div>

    <div class="Slide-Container">
        <?php include('./component/slideShow.php'); ?>

    </div>
    <center>
        <div class="product-container">
            <?php
            include('./component/getFunction/getProductImages.php');
            $cx = mysqli_connect("localhost", "root", "", "shopping");
            $cur = "SELECT * FROM product";
            $msresults = mysqli_query($cx, $cur);

            if (mysqli_num_rows($msresults) > 0) {
                while ($row = mysqli_fetch_array($msresults)) {
                    echo "<div class='product-card'>
                        <img class='product-image' src='" . getProductImage($row['ProID']) . "'>
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
    </center>
</body>

</html>