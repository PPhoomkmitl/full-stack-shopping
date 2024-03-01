<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
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
</style>
<body>
    <a class="aBack" href='?aBack=1'> <- กลับไปหน้าหลัก </a>
    <?php
        // เช็คว่ามีการคลิกลิงก์ aBack หรือไม่
        if (isset($_GET['aBack'])) {
            // Unset session ที่คุณต้องการ
            unset($_SESSION['guest']);
            unset($_SESSION['id_username']);
            // สามารถเพิ่มการ redirect ไปที่หน้าหลักหรือหน้าอื่น ๆ ได้ตามต้องการ
            header("Location: index.php");
            exit();
        }
    ?>
</body>
</html>