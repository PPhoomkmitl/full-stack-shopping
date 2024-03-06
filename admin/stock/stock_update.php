<?php
/* get connection */
include_once '../../dbConfig.php'; 

/* SELECT */
$code = $_POST['id_stock'];
$cur = "SELECT * FROM product WHERE ProID = '$code'";
$msresults = mysqli_query($conn, $cur);

// Select
if (mysqli_num_rows($msresults) > 0) {
    $row = mysqli_fetch_array($msresults);

    echo "<form method='post' action='stock_save_update.php'>";
    echo "<center>";
    echo "<div style='max-width: 400px;'>";
    echo "<h1 style='text-align: center; color: #3498db;'>Update Stock</h1>";
    echo "<h2>สินค้า ID" . $row['ProID'] . "</h2><br>";
    echo "<input type='hidden' name='a1' value='" . $row['ProID'] . "'>";
    echo "ชื่อสินค้า <input type='text' name='a2' value='" . $row['ProName'] . "' style='width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;'><br>";
    echo "ราคาต่อหน่วย (บาท) <input type='text' name='a3' value='" . $row['PricePerUnit'] . "' style='width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;'><br>";
    echo "จำนวน <input type='text' name='a4' value='" . $row['StockQty'] . "' style='width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;'><br>";
    echo "onHand <input disabled type='text' name='a5' value='" . $row['OnHands'] . "' style='width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;'><br>";

    echo "<div style='text-align: center;'>";
    echo "⚠️ โปรดตรวจสอบให้แน่ใจว่าคุณต้องการอัปเดตข้อมูล ⚠️<br><br>";
    echo "<input type='reset' value='รีเซ็ท' style='background-color: #ef476f; color: #fff; padding: 10px 20px; border: none; cursor: pointer; margin-right:2rem; border-radius: 4px;'>";
    echo "<input type='submit' value='ยืนยัน' style='background-color: #3498db; color: #fff; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px;'>";
    echo "</div>";
    echo "</div>";
    echo "</form>\n";
    echo "</center>";
}

/* close connection */
mysqli_close($conn);
?>
