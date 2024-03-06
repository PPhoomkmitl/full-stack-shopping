<?php
header("location: ./stock_index.php");
include_once '../../dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a2 = $_POST['a2'];
    $a3 = $_POST['a3'];
    $a4 = $_POST['a4'];
    $a5 = $_POST['a5'];

    /* run insert */
    $stmt = mysqli_query($conn, "INSERT INTO product(ProName, Description ,PricePerUnit, StockQty)
        VALUES('$a2', '$a5' ,'$a3', '$a4')");
    
    /* check for errors */
    if ($stmt) {
        // Get the last inserted ID
        $lastInsertedId = mysqli_insert_id($conn);
    
        // Check if the image file was uploaded
        if (isset($_FILES["image"])) {
            $file = $_FILES["image"];
            $imageData = file_get_contents($file["tmp_name"]); // Read image data as binary
    
            // Update the product record in the database with the image data
            $updateImageQuery = "UPDATE product SET ImageData = ? WHERE ProID = ?";
            $stmt = mysqli_prepare($conn, $updateImageQuery);
            mysqli_stmt_bind_param($stmt, 'si', $imageData, $lastInsertedId);
            mysqli_stmt_send_long_data($stmt, 0, $imageData); // Send binary data
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    echo "<a href='stock_index.php' 
    style='
    padding: 9px 14px;
    color: #ef476f;             
    text-decoration: none;
    margin-right: 5px;
    '>กลับหน้าหลัก</a>";

    mysqli_close($conn);
}
?>
