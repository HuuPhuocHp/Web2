<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $CustomerId = $_GET['id'];

    $query = "UPDATE Customers SET Status = 0 WHERE CustomerId = '$CustomerId'";
    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Người dùng đã bị khóa!"); window.location.href = "user_list.php";</script>';
    } else {
        echo '<script>alert("Khóa người dùng thất bại!"); window.location.href = "user_list.php";</script>';
    }
}
?>