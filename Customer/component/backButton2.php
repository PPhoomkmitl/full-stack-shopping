<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Document</title>
</head>
<style>
    a.aBack {
        margin-top: 1%;
        margin-left: 7%;
        display: inline-block;
        padding: 5px 28px;
        background-color: #4CAF50;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    a.aBack:hover {
        background-color: #28A745;
    }
</style>
<body>
<?php


// เช็คว่ามีการคลิกลิงก์ aBack หรือไม่
if (isset($_GET['aBack'])) {
    // Unset session ที่คุณต้องการ
    unset($_SESSION['guest']);
    unset($_SESSION['id_username']);

    // Redirect to index.php
    header("Location: index.php");
    exit();// สามารถเพิ่มการ redirect ไปที่หน้าหลักหรือหน้าอื่น ๆ ได้ตามต้องการ
}
?>
    <a class="aBack" href="index.php">
        <i class="fas fa-arrow-left"></i>
    </a>
</body>
</html>
