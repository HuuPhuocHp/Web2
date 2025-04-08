<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";  // Địa chỉ của máy chủ cơ sở dữ liệu (localhost nếu bạn đang làm việc trên máy tính của mình)
$username = "root";         // Tên người dùng của cơ sở dữ liệu (ví dụ: root)
$password = "";             // Mật khẩu của người dùng cơ sở dữ liệu (nếu có)
$dbname = "test";           // Tên cơ sở dữ liệu (ở đây là 'test')

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nếu kết nối thành công, sẽ không có thông báo gì, kết nối sẵn sàng để sử dụng
?>
