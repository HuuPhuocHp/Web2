<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $sql = "DELETE FROM products WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: QLSP.php");  // Quay lại trang danh sách sản phẩm sau khi xóa
        exit();
    } else {
        echo "Lỗi xóa sản phẩm: " . $conn->error;
    }
}

$conn->close();
?>
