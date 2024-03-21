<?php
// เชื่อมต่อกับฐานข้อมูล
include_once '../dbConfig.php';
include('./component/session.php'); 

// ตรวจสอบว่ามีข้อมูลที่ส่งมาผ่าน POST หรือไม่
if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    // รับค่า order_id และ new_status จากการส่งมาผ่าน POST
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $uid = $_SESSION['id_username'];



    if ($_POST['new_status'] == 'Add-new-cart') {
        // สร้างคำสั่ง SQL สำหรับอ่าน product_id จากตาราง order_details
        $cart_item = "SELECT * FROM cart WHERE CusID = $uid";
        $result_cart_item = mysqli_query($conn, $cart_item);

        // ตรวจสอบว่ามีข้อมูลในตาราง cart ก่อนที่จะทำการลบหรือไม่
        if(mysqli_num_rows($result_cart_item) > 0) {
            $delete_query = "DELETE FROM cart WHERE CusID = $uid";
            if (!mysqli_query($conn, $delete_query)) {
                echo "Error deleting existing items from cart: " . mysqli_error($conn);
                exit(); // หยุดการทำงานถ้าเกิดข้อผิดพลาด
            }
        }

        // ดำเนินการเพิ่มรายการสินค้าใหม่ลงในตะกร้า
        $sql = "SELECT ProID, quantity FROM order_details WHERE order_id = $order_id";
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            // วนลูปผลลัพธ์และเพิ่มสินค้าใหม่ลงในตะกร้าสำหรับแต่ละ product_id
            while ($row = mysqli_fetch_assoc($result)) {
                $product_id = $row['ProID'];
                $quantity = $row['quantity'];
    
                // เพิ่มรายการสินค้าใหม่ลงในตะกร้า
                $sql = "INSERT INTO cart (CusID, ProID, Qty) VALUES ($uid, $product_id, $quantity)";

                // ทำการ execute คำสั่ง SQL
                if (!mysqli_query($conn, $sql)) {
                    echo "Error adding new item to cart: " . mysqli_error($conn);
                    exit(); // หยุดการทำงานถ้าเกิดข้อผิดพลาด
                }
            }
        } else {
            echo "Error fetching product IDs from order details: " . mysqli_error($conn);
            exit(); // หยุดการทำงานถ้าเกิดข้อผิดพลาด
        }


    } else if($_POST['new_status'] == 'Canceled') {
        // กรณีที่ไม่ใช่การเพิ่มรายการใหม่ลงในตะกร้า
        $sql = "UPDATE orders SET shipping_status = '$new_status' WHERE order_id = $order_id";
        mysqli_query($conn, $sql);
    }



    // ทำการอัปเดตต่อข้อมูล
    if ($_POST['new_status'] == 'Add-new-cart') {
        // ถ้าอัปเดตข้อมูลสำเร็จ
        echo json_encode(['status' => 'success', 'message' => 'Add-new-cart']);
        exit();
    } 
        
    else if($_POST['new_status'] == 'Canceled') {
        // ถ้าอัปเดตข้อมูลสำเร็จ
        echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
        exit();
    }
        
    else {
        // ถ้าเกิดข้อผิดพลาดในการอัปเดตข้อมูล
        echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
        exit();
    }
        
    } else {
        // ถ้าไม่มีข้อมูลที่ส่งมาผ่าน POST
        echo json_encode(['status' => 'error', 'message' => 'Missing order_id or new_status' , 'comment' => $_POST['new_status']]);
        http_response_code(400); 
        exit();
    }
?>