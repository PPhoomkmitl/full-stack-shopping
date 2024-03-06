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