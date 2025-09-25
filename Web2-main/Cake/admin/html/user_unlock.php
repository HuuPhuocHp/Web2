<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $CustomerId = $_GET['id'];

    $query = "UPDATE Customers SET Status = 1 WHERE CustomerId = '$CustomerId'";
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Người dùng đã được mở khóa!"); window.location.href = "user_list.php";</script>';
    } else {
        echo '<script>alert("Mở khóa người dùng thất bại!"); window.location.href = "user_list.php";</script>';
    }
}
?>