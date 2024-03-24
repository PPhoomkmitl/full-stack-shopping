<?php 
    include('./component/session.php'); 
    include_once '../dbConfig.php'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <!-- Bootstrap Icons -->
    <link href="path/to/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- เรียกใช้งาน Bootstrap JavaScript รวมถึงโมดูล Popper.js ที่ต้องใช้
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 100px auto auto auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 100px;
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        .order {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #F7F7F7;
        }

        .order p {
            padding: 5px 0;
        }

        .order pf {
            font-size: 1.2em;
            font-weight: bold;
        }

        .order pl {
            font-size: 1.2em;
            color: #ffff;
            border-radius: 10px;
        }

        /* #Paid {
            padding: 3px 8px;
            background-color: #06D6B1;
        }

        #Unpaid {
            padding: 3px 8px;
            background-color: #F0476F;
        }*/

        #Pending-status {
            color: #06D6B1;
            margin-top: 5px;
        } 

        #Inprogress-status {
            color: #06D6B1;
            margin-top: 5px;
        }

        #Delivered-status {
            /* padding: 3px 8px; */
            /* background-color: #06D6B1; */
            color: #06D6B1;
            margin-top: 5px;
        }

        #Canceled-status {
            color: #FC4141;
            margin-top: 5px;
        } 



        .icon-container {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            border: none;
        }

        
        .button-container {
            display: flex;
            justify-content: center; /* จัดตำแหน่งปุ่มไปทางขวา */
            
            /* background-color: #FE6233; */
            /* color: #ffff; */
        }

        .button-container button {
            background-color: #FE6233;
            width: 100%;
            color: #ffff;
        }

        .button-container button:hover {
            background-color: #EE4D2D;
            color: #ffff;
        }



        .tab {
            overflow: hidden;
            border: 1px solid #fff;
            /* Added border bottom */
            margin-bottom: 10px;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            border-bottom: 2px solid transparent;
            /* Added transparent border */
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
            border-bottom: 2px solid #3498db;
            /* Underline color */
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .navCon {
            z-index: 100;
            border: 1px solid #333;
        }

      

      
    </style>
</head>
<!-- <?php include('./component/backButton.php'); ?> -->

<body>
    <div class="navCon">
        <?php include('./component/accessNavbar.php'); ?>
    </div>
    <div class="container">
        <h1>History</h1>
        <!-- Tab buttons -->
        <div class="tab">
            <button id="pendingTab" class="tablinks" onclick="openTab(event, 'pending')">Pending</button>
            <button class="tablinks" onclick="openTab(event, 'inprogress')">Inprogress</button>
            <button class="tablinks" onclick="openTab(event, 'delivered')">Delivered</button>
            <button class="tablinks" onclick="openTab(event, 'canceled')">Canceled</button>
        </div>

        <?php
        $uid = $_SESSION['id_username'];
        ?>

        <!-- Tab content -->
        <div id="pending" class="tabcontent">
            <?php includeOrders("SELECT * FROM orders WHERE CusID = '$uid' AND (shipping_status = 'Pending' OR (shipping_status = 'Canceled' AND fullfill_status = 'Unfulfilled'))", $conn); ?>
        </div>

        <div id="inprogress" class="tabcontent">
            <?php includeOrders("SELECT * FROM orders WHERE CusID = '$uid' AND shipping_status = 'Inprogress'", $conn); ?>
        </div>

        <div id="delivered" class="tabcontent">
            <?php includeOrders("SELECT * FROM orders WHERE CusID = '$uid' AND shipping_status = 'Delivered'", $conn); ?>
        </div>

        <div id="canceled" class="tabcontent">
            <?php includeOrders("SELECT * FROM orders WHERE CusID = '$uid' AND shipping_status = 'Canceled' AND fullfill_status = 'Fulfilled'", $conn); ?>
        </div>

    </div>
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("pendingTab").click();
    </script>
    <script>
        function updateStatus(order_id, new_status) {
            console.log(order_id, new_status);
            $.ajax({
                type:'POST',
                url:'updateStatus.php',
                data: { order_id: order_id, new_status: new_status },
                success: function(response) {
                    // console.log(response); // ล็อกข้อมูลทั้งหมดที่ได้รับกลับมาจากการเรียก Ajax
                    
                    var jsonResponse = JSON.parse(response);
                    console.log(jsonResponse); 
                    console.log(jsonResponse.message); 

                    if (jsonResponse.message === "Add-new-cart") {
                        console.log('check');
                        window.location.assign('./cart.php');
                    }
                    
                }, 
                error: function(error) {
                    console.error('Error fetching filtered data:', error);
                }
            });

            // ตัวอย่างเทส: พิมพ์ข้อความออกมาเพื่อแสดงว่าการอัปเดตเสร็จสมบูรณ์
            console.log('Order ID ' + order_id + ' updated to ' + new_status);
            
        }
    </script>
</body>

</html>

<?php


    function includeOrders($query , $conn) { 
        $msresults = mysqli_query($conn, $query);
        
        while ($row = mysqli_fetch_array($msresults)) {
   
                if($row['shipping_status'] == 'Inprogress') {
                    echo '<div class="order">';
                    echo "<div class='icon-container'>
                            <form method='post' action='bill.php'>
                                <input type='hidden' name='id_order' value='{$row['order_id']}'>
                                <button type='submit'>
                                    <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                                </button>
                            </form>
                        </div>";
                    echo "<pf>Order ID: {$row['order_id']}</pf>";
                    echo "<hr>";
                    echo "<p>Total Amount: {$row['total_price']} ฿</p>";
                    echo "<p>Order Date: {$row['order_date']}</p>";
                    if ($row['delivery_date'] != null) {
                        echo "<p>Delivery Date: {$row['delivery_date']}</p>";
                    }
                    echo "<hr>";

                    echo "<pl id='{$row['shipping_status']}-status'><img src='./image/noun-shipping.png' alt='Shipping Fast Icon' width='20'> {$row['shipping_status']}</pl>";
                    echo "<hr>";
                    // echo "<button type='button' class='btn btn-primary' onclick='updateStatus({$row['order_id']}, 'Canceled')'>Canceled</button>";
                    // <button type='button' class='btn btn-primary' onclick='updateStatus({$row['order_id']}, 'Canceled')'>Canceled</button>
                    echo "<div class='button-container'>
                    
                </div>";
                }

                else if($row['shipping_status'] == 'Pending' || ($row['shipping_status'] == 'Canceled' && $row['fullfill_status'] == 'Unfulfilled')) {
                    echo '<div class="order">';
                    echo "<div class='icon-container'>
                            <form method='post' action='bill.php'>
                                <input type='hidden' name='id_order' value='{$row['order_id']}'>
                                <button type='submit'>
                                    <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                                </button>
                            </form>
                        </div>";
                    echo "<pf>Order ID: {$row['order_id']}</pf>";
                    echo "<hr>";
                    echo "<p>Total Amount: {$row['total_price']} ฿</p>";
                    echo "<p>Order Date: {$row['order_date']}</p>";
                    if ($row['delivery_date'] != null) {
                        echo "<p>Delivery Date: {$row['delivery_date']}</p>";
                    }
                    echo "<hr>";

                   
                    if($row['shipping_status'] == 'Canceled' && $row['fullfill_status'] == 'Unfulfilled'){
                        echo "<div class='button-container'>
                        <button type='button' class='btn btn-secondary' disabled style='color: #fff; border-color: #f8f9fa;'>
                            Waiting for approval to cancel...
                        </button>
                        </div>";
                    }
                    else {
                        echo "<pl id='{$row['shipping_status']}-status'><img src='./image/pending.png' alt='Pending Fast Icon' width='20'> {$row['shipping_status']}</pl>";
                        echo "<hr>";
    
                        echo "<div class='button-container'>
                        <button type='button' class='btn' onclick='updateStatus({$row['order_id']}, \"Canceled\")'>
                            Canceled
                        </button>
                        </div>";
                    }       

                }

                else if($row['shipping_status'] == 'Delivered') {
                    echo '<div class="order">';
                    echo "<div class='icon-container'>
                            <form method='post' action='bill.php'>
                                <input type='hidden' name='id_order' value='{$row['order_id']}'>
                                <button type='submit'>
                                    <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                                </button>
                            </form>
                        </div>";
                    echo "<pf>Order ID: {$row['order_id']}</pf>";
                    echo "<hr>";
                    echo "<p>Total Amount: {$row['total_price']} ฿</p>";
                    echo "<p>Order Date: {$row['order_date']}</p>";
                    if ($row['delivery_date'] != null) {
                        echo "<p>Delivery Date: {$row['delivery_date']}</p>";
                    }
                    echo "<hr>";

                    echo "<pl id='{$row['shipping_status']}-status'><img src='./image/shipping-fast.png' alt='Delivered Fast Icon' width='20'> {$row['shipping_status']}</pl>";
                    echo "<hr>";
                    echo "<div class='button-container'>
                        <button type='button' class='btn' onclick='updateStatus({$row['order_id']}, \"Add-new-cart\")'>
                            Order again
                        </button>
                    </div>";
                }

                else if($row['shipping_status'] == 'Canceled' && $row['fullfill_status'] == 'Fulfilled') {
                    echo '<div class="order">';
                    echo "<div class='icon-container'>
                            <form method='post' action='bill.php'>
                                <input type='hidden' name='id_order' value='{$row['order_id']}'>
                                <button type='submit'>
                                    <img src='./image/search-alt.png' alt='Order Icon' width='20'>
                                </button>
                            </form>
                        </div>";
                    echo "<pf>Order ID: {$row['order_id']}</pf>";
                    echo "<hr>";
                    echo "<p>Total Amount: {$row['total_price']} ฿</p>";
                    echo "<p>Order Date: {$row['order_date']}</p>";
                    if ($row['delivery_date'] != null) {
                        echo "<p>Delivery Date: {$row['delivery_date']}</p>";
                    }
                    echo "<hr>";

                    echo "<pl id='{$row['shipping_status']}-status'><img src='./image/cross-circle.png' alt='Canceled Fast Icon' width='20'> {$row['shipping_status']}</pl>";
                    echo "<hr>";
                }
         
                
                echo '</div>';
    
            }          
        }
?>