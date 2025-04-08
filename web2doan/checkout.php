<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    unset($_SESSION['cart']); // Xóa giỏ hàng sau khi thanh toán
    echo json_encode(["status" => "success", "message" => "Thanh toán thành công!"]);
}
?>
