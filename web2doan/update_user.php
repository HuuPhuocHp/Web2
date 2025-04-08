<?php
// Bao gồm tệp kết nối cơ sở dữ liệu
include 'db_connect.php';

// Kiểm tra xem form có được gửi hay không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form (có thể lấy từ một form sửa khách hàng)
    $id = $_POST['id'];  // ID khách hàng
    $name = $_POST['name'];  // Tên khách hàng
    $address = $_POST['address'];  // Địa chỉ
    $phone = $_POST['phone'];  // Số điện thoại
    $email = $_POST['email'];  // Email

    // Câu lệnh SQL để cập nhật thông tin khách hàng
    $query = "UPDATE customers SET name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $address, $phone, $email, $id);

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        echo "Cập nhật khách hàng thành công!";
    } else {
        echo "Có lỗi xảy ra: " . $stmt->error;
    }
}
?>
