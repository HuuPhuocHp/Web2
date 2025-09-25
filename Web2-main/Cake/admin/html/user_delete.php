<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $CustomerId = $_GET['id'];

    // Kiểm tra xem tài khoản có đơn hàng hay không
    $checkOrdersQuery = "SELECT * FROM oders WHERE CustomerId = '$CustomerId'";
    $result = mysqli_query($conn, $checkOrdersQuery);

    if (mysqli_num_rows($result) > 0) {
        // Nếu tài khoản có đơn hàng, hiển thị thông báo xác nhận
        echo '<script>
            if (confirm("Tài khoản này đang có đơn hàng liên quan. Bạn có chắc chắn muốn xóa không?")) {
                window.location.href = "user_delete_confirm.php?id=' . $CustomerId . '";
            } else {
                window.location.href = "user_list.php";
            }
        </script>';
    } else {
        // Nếu không có đơn hàng, xóa tài khoản
        $query = "DELETE FROM Customers WHERE CustomerId = '$CustomerId'";
        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Người dùng đã được xóa!"); window.location.href = "user_list.php";</script>';
        } else {
            echo '<script>alert("Xóa người dùng thất bại!"); window.location.href = "user_list.php";</script>';
        }
    }
}
?>