<?php

function getProductImage($proID)
{
    // กำหนดเงื่อนไข ProID และ return รูปภาพที่ต้องการ
    if ($proID == 14) {
        return './image/sock.jpg';
    } elseif ($proID == 15) {
        return './image/book4.jpg';
    } elseif ($proID == 16) {
        return './image/nb4.gif';
    } else {
        // ถ้า ProID ไม่ตรงกับที่กำหนดให้ใช้รูป cart.png หรือสามารถกำหนดเงื่อนไขเพิ่มเติมได้ตามต้องการ
        return './image/cart.png';
    }
}

function getSetOfProductImage($proID)
{
    if ($proID == 14) {
        return './image/sock.jpg';
    } elseif ($proID == 15) {
        return './image/book4.jpg';
    } elseif ($proID == 16) {
        return './image/nb4.gif';
    } else {
        // ถ้า ProID ไม่ตรงกับที่กำหนดให้ใช้รูป cart.png หรือสามารถกำหนดเงื่อนไขเพิ่มเติมได้ตามต้องการ
        return './image/cart.png';
    }
}
