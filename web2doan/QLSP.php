<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sneaker Hub - Official Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Cải thiện bảng sản phẩm */
        .product-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-list th, .product-list td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .product-list th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #333;
        }

        .product-list td {
            background-color: #fff;
        }

        .product-list tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .product-list tr:hover {
            background-color: #f1f1f1;
        }

        .product-list img {
            max-width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .product-list .btn {
            background-color: #007BFF;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }

        .product-list .btn:hover {
            background-color: #0056b3;
        }

        /* Tạo khoảng cách cho các nút sửa và xóa */
        .product-list td a {
            display: inline-block;
            margin-right: 10px;
        }

        /* Đảm bảo bảng không bị tràn ra ngoài */
        .container {
            overflow-x: auto;
        }

        /* Cải thiện độ dài của tiêu đề bảng */
        .product-list th, .product-list td {
            min-width: 120px;
        }

        /* Cải thiện khoảng cách giữa các cột */
        .product-list th, .product-list td {
            padding: 15px;
        }

        /* Chỉnh sửa khoảng cách cho bảng */
        .product-list {
            margin-top: 20px;
        }

        /* Cải thiện độ cân đối cho tiêu đề */
        .product-list h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Cải thiện sự tương tác của bảng */
        .product-list tr:hover td {
            background-color: #e9ecef;
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
                        <li><a href="QLKH.php">Quản lý Khách Hàng</a></li>
                        <li><a href="#">Thống kê sản phẩm</a></li>
                        <li><a href="#">Thống kê khách hàng</a></li>
                    </ul>
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

    <div class="container">
        <h1 class="text-center">Quản Lý Sản Phẩm</h1>
        <a href="add_product.html" class="btn">Thêm Sản Phẩm Mới</a>
    </div>

    <section class="product-list">
        <h2 class="text-center">Danh Sách Sản Phẩm</h2>

        <table>
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Size</th>
                    <th>Mô tả</th>
                    <th>Hình ảnh</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Kết nối cơ sở dữ liệu
            $conn = new mysqli('localhost', 'root', '', 'test');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['product_price'] . "</td>";
        echo "<td>" . $row['product_size'] . "</td>";
        echo "<td>" . $row['product_description'] . "</td>";
        
        // Hiển thị ảnh sản phẩm từ thư mục uploads
        echo "<td><img src='anhsanpham/" . $row['product_image'] . "' alt='Sản phẩm' width='100'></td>";
        echo "<td><a href='edit_product.php?id=" . $row['id'] . "' class='btn-edit'>Chỉnh sửa</a> <a href='delete_product.php?id=" . $row['id'] . "' class='btn-delete'>Xóa</a></td>";
        echo "</tr>";
    }
} else {
    echo "Không có sản phẩm!";
}

            $conn->close();
            ?>
            </tbody>
        </table>
    </section>
</body>
</html>
