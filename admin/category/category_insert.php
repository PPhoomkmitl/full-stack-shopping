<?php
header("location: ./category_index.php");
include_once '../../dbConfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a2 = $_POST['a2'];



    /* run insert */
    $stmt = mysqli_query($conn, "INSERT INTO product_type(name) VALUES('$a2')");
    
    /* check for errors */
    if ($stmt) {
        // Get the last inserted ID
        $lastInsertedId = mysqli_insert_id($conn);
    
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
