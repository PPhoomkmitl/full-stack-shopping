<?php
// Start session
session_start();

if (!isset($_SESSION['admin'])) {
    // หากไม่ได้ login เป็น admin, redirect ไปยังหน้า login
    header("Location: ../login.php");
    exit;
}

?>

