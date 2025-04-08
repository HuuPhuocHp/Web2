<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận dữ liệu từ form
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_size = isset($_POST['product_size']) ? $_POST['product_size'] : 'N/A';
    $product_description = isset($_POST['product_description']) ? $_POST['product_description'] : 'Không có mô tả';

    // Xử lý hình ảnh
    $target_dir = "anhsanpham/"; // Thư mục chứa hình ảnh
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $image_url = null;

    // Kiểm tra và di chuyển file ảnh vào thư mục anhsanpham
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        $image_url = $target_file; // Lưu đường dẫn ảnh vào biến
    } else {
        echo "Có lỗi khi tải lên hình ảnh.";
        exit;  // Dừng lại nếu có lỗi
    }

    // Kết nối cơ sở dữ liệu và thêm sản phẩm
    $conn = new mysqli('localhost', 'root', '', 'test');  // Đảm bảo rằng 'test' là tên đúng của cơ sở dữ liệu của bạn
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Thêm sản phẩm vào bảng 'products'
    $sql = "INSERT INTO products (product_name, product_price, product_size, product_description, product_image)
            VALUES ('$product_name', '$product_price', '$product_size', '$product_description', '$image_url')";

    if ($conn->query($sql) === TRUE) {
        // Sau khi thêm sản phẩm, chuyển hướng về trang QLSP.php
        header("Location: QLSP.php");  // Điều hướng trở lại trang quản lý sản phẩm
        exit();  // Dừng mã tiếp theo sau khi chuyển hướng
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
