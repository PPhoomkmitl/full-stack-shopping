<?php
/* get connection */
include_once '../../dbConfig.php'; 

$cusID = $_POST['id_customer'];
$recvID = $_POST['id_receiver'];
$a1 = $_POST['a1'];
$a2 = $_POST['a2'];
$a3 = $_POST['a3'];
$a4 = $_POST['a4'];
$a5 = $_POST['a5'];

/* run update */
$stmt = mysqli_query($conn, "UPDATE customer SET CusFName = '$a1', CusLName = '$a2', sex = '$a3', Tel = '$a4' WHERE CusID = '$cusID'");

if ($recvID == '') {
    $stmt_receiver_head = mysqli_query($conn, "INSERT INTO receiver(RecvFName, RecvLName, sex, Tel, Address)
        VALUES('$a1', '$a2', '$a3', '$a4', '$a5')");

    if ($stmt_receiver_head) {
        // Get the last inserted ID from the receiver table
        $recvID = mysqli_insert_id($conn);

        // Generate a new NumID for receiver_detail
        $resultDetail = mysqli_query($conn, "SELECT MAX(NumID) AS num_id FROM receiver_detail WHERE RecvID = '$recvID'");
        $row2 = mysqli_fetch_assoc($resultDetail);
        $lastID = $row2['num_id'];
        $numericPart = intval(substr($lastID, 3));
        $newNumericPart = $numericPart + 1;
        $NumID = 'Num' . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);

        $stmt_receiver_detail = mysqli_query($conn, "INSERT INTO receiver_detail(CusID, RecvID, NumID)
            VALUES('$cusID', '$recvID', '$NumID')");

        if (!$stmt_receiver_detail) {
            echo "มีข้อผิดพลาดในการเพิ่มข้อมูลใน receiver_detail: " . mysqli_error($conn);
        }
    } else {
        echo "มีข้อผิดพลาดในการเพิ่มข้อมูลใน receiver: " . mysqli_error($conn);
    }
} else {
    $stmt_receiver = mysqli_query($conn, "UPDATE receiver SET Address = '$a5' WHERE RecvID = '$recvID'");
    if (!$stmt_receiver) {
        echo "มีข้อผิดพลาดในการอัปเดตข้อมูลใน receiver: " . mysqli_error($conn);
    }
}

/* check for errors */
if (!$stmt) {
    echo "Error";
} else {
    echo "Update data = <font color=red> '$a1' </font> is Successful.";
}

/* close connection */
mysqli_close($conn);
?>
