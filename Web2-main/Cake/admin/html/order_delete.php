<?php
include ($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/admin/html/order_model.php");

// Khởi tạo đối tượng OrderModel
$orderModel = new OrderModel($conn);

// Kiểm tra nếu id được truyền từ URL
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Gọi phương thức deleteOrder từ OrderModel
    $delete = $orderModel->deleteOrder($id);

    // Kiểm tra kết quả và thông báo cho người dùng
    if($delete){
        echo '<script language="javascript">';
        echo 'alert("Xóa đơn hàng thành công!")';
        echo '</script>';
        // Chuyển hướng sau khi hiển thị thông báo
        echo '<script>window.location.href="order_list.php";</script>';
        exit(); // Dừng thực thi mã sau khi chuyển hướng
    } else {
        echo "Xảy ra lỗi khi xóa đơn hàng.";
    }
}
?>
