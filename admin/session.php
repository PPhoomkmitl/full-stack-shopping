<?php
// Start session
session_start();
// if (!isset($_SESSION['admin'])) {
    
//     // หากไม่ได้ login เป็น admin, redirect ไปยังหน้า login
//     header("Location: ../../Customer/login.php");
//     exit;
// }
var_dump($_SESSION);
?>

