<?php
include_once '../../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ใช้ $_POST แทน $_GET เนื่องจากข้อมูลถูกส่งด้วย POST
    $InvID = $_POST['InvID'];
    $newStatus = $_POST['newStatus'];

    // ป้องกัน SQL Injection ด้วย mysqli_real_escape_string
    $recID = mysqli_real_escape_string($conn, $InvID);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

   
    $updateQuery = "UPDATE invoice SET Status = '$newStatus' WHERE InvID = '$InvID'";

    mysqli_query($conn, $updateQuery);

    // คำสั่ง SQL UPDATE อาจให้ผลลัพธ์หลายรายการ, คุณสามารถตรวจสอบว่ามีการแก้ไขข้อมูลจริงๆ ไหม
    $affectedRows = mysqli_affected_rows($conn);

    if ($affectedRows > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "error" => "No records updated"));
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>
