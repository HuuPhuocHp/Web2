<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'test');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy thông tin sản phẩm từ cơ sở dữ liệu
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại!";
        exit();
    }
}

// Cập nhật thông tin sản phẩm khi form được submit
// Xử lý khi người dùng chỉnh sửa sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_size = $_POST['product_size'];
    $product_description = $_POST['product_description'];
    $product_image = $_FILES['product_image']['name'];  // Lấy tên file ảnh mới

    // Kiểm tra nếu có ảnh mới được tải lên
    if ($product_image != "") {
        $target_dir = "anhsanpham/";  // Thư mục lưu ảnh
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        
        // Di chuyển ảnh từ tạm thời tới thư mục uploads
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Cập nhật đường dẫn ảnh mới vào cơ sở dữ liệu
            $sql = "UPDATE products SET 
                    product_name = '$product_name', 
                    product_price = '$product_price', 
                    product_size = '$product_size', 
                    product_description = '$product_description', 
                    product_image = '$product_image' 
                    WHERE id = $product_id";
        } else {
            echo "Lỗi tải ảnh lên!";
        }
    } else {
        // Nếu không có ảnh mới, chỉ cập nhật các thông tin khác
        $sql = "UPDATE products SET 
                product_name = '$product_name', 
                product_price = '$product_price', 
                product_size = '$product_size', 
                product_description = '$product_description' 
                WHERE id = $product_id";
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    if ($conn->query($sql) === TRUE) {
        header("Location: QLSP.php");  // Quay lại trang quản lý sản phẩm sau khi cập nhật
        exit();
    } else {
        echo "Lỗi cập nhật sản phẩm: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaker Hub - Official Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
/* CSS cho phần chỉnh sửa sản phẩm */
body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

/* Container chính */
.form-container {
    max-width: 800px; /* Giới hạn chiều rộng */
    margin: 40px auto; /* Căn giữa */
    background: linear-gradient(135deg, #ffeb3b, #ff9800); /* Gradient nền vàng-cam nổi bật */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Bóng đậm để nổi bật */
    border: 2px solid #ff6200; /* Viền cam */
}

/* Tiêu đề */
h1 {
    color: #d32f2f; /* Màu đỏ nổi bật cho tiêu đề */
    font-size: 32px;
    text-align: center;
    margin-bottom: 30px;
    font-weight: bold;
    text-transform: uppercase; /* Chữ in hoa */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Bóng chữ */
}

/* Form chỉnh sửa */
form {
    display: flex;
    flex-direction: column;
    align-items: center; /* Căn giữa các trường */
}

/* Label */
.form-container label {
    display: block;
    font-weight: bolder; /* In đậm hơn (giá trị cao hơn 700 để đậm hơn nữa) */
    margin-bottom: 8px;
    color:rgb(3, 0, 1); /* Màu hồng đậm nổi bật */
    font-size: 18px;
    text-align: left;
    width: 100%;
    max-width: 500px; /* Giới hạn chiều rộng label */
    text-transform: uppercase; /* Chữ in hoa */
}

/* Input và Textarea */
.form-container input,
.form-container textarea {
    width: 100%;
    max-width: 500px; /* Giới hạn chiều rộng để căn giữa */
    padding: 12px;
    margin-bottom: 20px;
    border: 2px solid #ff6200; /* Viền cam nổi bật */
    border-radius: 8px;
    font-size: 16px;
    box-sizing: border-box;
    background-color: #fff9c4; /* Nền vàng nhạt */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Bóng nhẹ */
    transition: all 0.3s ease;
}

.form-container input:focus,
.form-container textarea:focus {
    border-color: #d81b60; /* Viền hồng đậm khi focus */
    background-color: #fff; /* Nền trắng khi focus */
    box-shadow: 0 0 10px rgba(216, 27, 96, 0.5); /* Bóng hồng khi focus */
    outline: none;
}

.form-container textarea {
    min-height: 120px;
    resize: vertical;
}

/* Button */
.form-container button {
    background: linear-gradient(135deg, #ff4081, #d81b60); /* Gradient hồng đậm */
    color: #fff;
    padding: 15px 40px;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
    max-width: 250px; /* Giới hạn chiều rộng nút */
    transition: all 0.3s ease;
    text-transform: uppercase; /* Chữ in hoa */
    box-shadow: 0 4px 15px rgba(216, 27, 96, 0.4); /* Bóng hồng */
}

.form-container button:hover {
    background: linear-gradient(135deg, #d81b60, #ff4081); /* Đảo gradient khi hover */
    transform: translateY(-2px); /* Nâng nút lên khi hover */
    box-shadow: 0 6px 20px rgba(216, 27, 96, 0.6); /* Bóng đậm hơn */
}

/* Responsive design */
@media (max-width: 768px) {
    .form-container {
        max-width: 90%;
        padding: 20px;
    }

    h1 {
        font-size: 24px;
    }

    .form-container label,
    .form-container input,
    .form-container textarea,
    .form-container button {
        font-size: 14px;
        max-width: 100%;
    }
}
    </style>
</head>
<body>
     <!-- Header -->
     <header class="header">
        <div class="container">
            <div class="header-top">
                <nav class="top-nav">
                    <ul>
                        <li><a href="#">Trang chủ</a></li>
                        <li><a href="QLSP.php">Quản lý sản phẩm</a></li>
                        <li><a href="#">Quản lý đơn hàng</a></li>
                        <li><a href="QLKH.php">Quản lý Khách Hàng</a>
                        <li><a href="#">Thống kê sản phẩm</a></li>
                        <li><a href="#">Thống kê khách hàng</a></li>
                </nav>
            </div>
            <div class="header-main">
                <div class="logo">
                    <a href="#"><img src="anh/logo.png" alt="Sneaker Hub"></a>
                </div>
       
                <div class="user-options">
                    <a href="Login.html">Khanh <i class="fas fa-user"></i></a>
                    <a href="logout.php">Đăng xuất</a>
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Banner -->
    <section class="main-banner">
        <div class="banner-content">
            <h1>You Got This</h1>
            <p>Discover the 2025 Collection – Power Your Performance</p>
            <a href="#" class="btn">Shop Now</a>
        </div>
    </section>
    <h1>Chỉnh sửa sản phẩm</h1>
    <form method="POST" action="">
        <label for="product-name">Tên sản phẩm:</label>
        <input type="text" id="product-name" name="product_name" value="<?php echo $row['product_name']; ?>" required><br>

        <label for="product-price">Giá:</label>
        <input type="number" id="product-price" name="product_price" value="<?php echo $row['product_price']; ?>" required><br>

        <label for="product-size">Size:</label>
        <input type="number" id="product-size" name="product_size" value="<?php echo $row['product_size']; ?>" required><br>

        <label for="product-description">Mô tả:</label>
        <textarea id="product-description" name="product_description" required><?php echo $row['product_description']; ?></textarea><br>
        <!-- Hiển thị ảnh hiện tại -->
<label>Ảnh hiện tại:</label>
<?php
if ($row['product_image']) {
    echo "<img src='anhsanpham/" . $row['product_image'] . "' alt='Ảnh sản phẩm' width='100'>";
} else {
    echo "Chưa có ảnh!";
}
?><br>

<!-- Thêm trường sửa ảnh -->
<label for="product-image">Chọn ảnh mới:</label>
<input type="file" id="product-image" name="product_image"><br>


        <button type="submit">Lưu thay đổi</button>
    </form>
</body>
</html>
