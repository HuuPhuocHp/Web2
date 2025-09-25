<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $CustomerId = $_GET['id'];

    // Xóa các đơn hàng liên quan đến tài khoản
    $deleteOrdersQuery = "DELETE FROM oders WHERE CustomerId = '$CustomerId'";
    mysqli_query($conn, $deleteOrdersQuery);

    // Xóa tài khoản
    $deleteCustomerQuery = "DELETE FROM Customers WHERE CustomerId = '$CustomerId'";
    if (mysqli_query($conn, $deleteCustomerQuery)) {
        echo '<script>alert("Người dùng và các đơn hàng liên quan đã được xóa!"); window.location.href = "user_list.php";</script>';
    } else {
        echo '<script>alert("Xóa người dùng thất bại!"); window.location.href = "user_list.php";</script>';
    }
}
?>