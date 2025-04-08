<?php
// Kết nối cơ sở dữ liệu
include 'db_connect.php';

// Kiểm tra nếu có ID khách hàng được truyền từ URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Câu lệnh SQL để xóa khách hàng
    $query = "DELETE FROM customers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng lại trang quản lý khách hàng
        header("Location: QLKH.php"); // Thay đổi thành trang của bạn nếu cần
        exit();
    } else {
        echo "Có lỗi xảy ra khi xóa khách hàng.";
    }
} else {
    echo "ID khách hàng không hợp lệ.";
}
?>
