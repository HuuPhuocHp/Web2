<?php
$id = $_GET['id'];
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "test");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT status FROM customers WHERE id = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

$new_status = ($user['status'] == 1) ? 0 : 1; // Đảo ngược trạng thái
$update_sql = "UPDATE customers SET status=$new_status WHERE id=$id";
$conn->query($update_sql);

$conn->close();

// Chuyển về trang quản lý khách hàng
header("Location: QLKH.html");
