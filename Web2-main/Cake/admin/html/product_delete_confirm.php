<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $ProductId = $_GET['id'];

    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $delete_query = "DELETE FROM Products WHERE ProductId = '$ProductId'";
    if (mysqli_query($conn, $delete_query)) {
        echo '<script>alert("Sản phẩm đã được xóa!"); window.location.href = "product_list.php";</script>';
    } else {
        echo '<script>alert("Xóa sản phẩm thất bại!"); window.location.href = "product_list.php";</script>';
    }
}
?>