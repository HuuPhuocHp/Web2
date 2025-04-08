<?php
// Bao gồm tệp kết nối cơ sở dữ liệu
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $name = $_POST['name'];  // Tên khách hàng
    $address = $_POST['address'];  // Địa chỉ
    $phone = $_POST['phone'];  // Số điện thoại
    $email = $_POST['email'];  // Email

    // Câu lệnh SQL để thêm khách hàng vào cơ sở dữ liệu
    $query = "INSERT INTO customers (name, address, phone_number, email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $address, $phone, $email);

    // Thực thi câu lệnh SQL
    if ($stmt->execute()) {
        // Sau khi thêm khách hàng, chuyển hướng trở lại trang quản lý khách hàng
        header("Location: QLKH.php");  // Chuyển hướng đến trang QLKH.html
        exit();  // Dừng thực thi mã còn lại
    } else {
        echo "Có lỗi xảy ra: " . $stmt->error;
    }
}
?>

<<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Phần nền menu trên cùng (header-top) */
        .header-top {
            background-color: #000; /* Nền màu đen cho menu */
            color: white;
            padding: 10px 0;
        }

        .header-top .top-nav ul {
            display: flex;
            justify-content: flex-end;
            list-style: none;
            gap: 20px;
        }

        .header-top .top-nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            text-transform: uppercase;
            transition: color 0.3s ease;
        }

        .header-top .top-nav ul li a:hover {
            color: #ff6600; /* Màu cam khi hover */
        }

        /* Phần nội dung header (logo và user options) */
        .header-main {
            background-color: #000; /* Nền màu đen cho phần nội dung */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .logo img {
            width: 120px; /* Kích thước logo */
        }

        .user-options a {
            color: #fff;
            margin-left: 20px;
            font-size: 18px;
            text-decoration: none;
        }

        .user-options a:hover {
            color: #ff6600; /* Màu cam khi hover */
        }

        /* Phần bảng khách hàng */
        .table-container {
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #ff6600;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .table td a {
            text-decoration: none;
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
        }

        .table td a.edit {
            background-color: #4CAF50;
            color: white;
        }

        .table td a.delete {
            background-color: #f44336;
            color: white;
        }

        .table td a:hover {
            opacity: 0.8;
        }

        /* Thêm khách hàng */
        .add-btn-container {
            text-align: center;
            margin-top: 30px;
        }

        .add-btn-container .action-btn.add {
            background-color: #4CAF50;
            font-size: 16px;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .add-btn-container .action-btn.add:hover {
            background-color: #45a049;
        }

        /* Phân trang */
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination-btn {
            padding: 10px 20px;
            background-color: #ff6600;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
            transition: 0.3s;
        }

        .pagination-btn:hover {
            background-color: #e65c00;
        }

        .pagination-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Phần menu điều hướng -->
    <div class="header-top">
        <div class="container">
            <nav class="top-nav">
                <ul>
                    <li><a href="#">Trang chủ</a></li>
                    <li><a href="#">Quản lý sản phẩm</a></li>
                    <li><a href="#">Quản lý đơn hàng</a></li>
                    <li><a href="QLKH.php">Quản Lý Khách Hàng</a></li>
                    <li><a href="#">Thống kê sản phẩm</a></li>
                    <li><a href="#">Thống kê khách hàng</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Phần logo và thông tin người dùng -->
    <header class="header-main">
        <div class="container">
            <div class="logo">
                <a href="#"><img src="anh/logo.png" alt="Sneaker Hub"></a>
            </div>
            <div class="user-options">
                <a href="Login.html">Khanh <i class="fas fa-user"></i></a>
                <a href="logout.php">Đăng xuất</a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
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
        <style>
            /* Căn giữa phần tiêu đề trong Add.html */
h1 {
    text-align: center; /* Căn giữa văn bản */
    font-size: 2em;
    margin-bottom: 20px;
    color: #333; /* Màu chữ */
}

        </style>
    <h1>Thêm Khách Hàng</h1>
    <style>
        form {
            display: flex;
            flex-wrap: wrap;  /* Để đảm bảo các phần tử có thể xuống dòng nếu cần */
            gap: 20px;  /* Khoảng cách giữa các trường */
            max-width: 900px;  /* Đảm bảo form không quá rộng */
            margin: 0 auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;  /* Căn chỉnh label và input theo chiều dọc */
            flex: 1 1 200px;  /* Đảm bảo các trường có chiều rộng đồng đều */
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;  /* Đảm bảo các input và select chiếm toàn bộ chiều rộng */
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Tên Khách Hàng:</label>
            <input type="text" id="name" name="name" required placeholder="Nhập tên khách hàng">
        </div>

        <div class="form-group">
            <label for="address">Địa Chỉ:</label>
            <input type="text" id="address" name="address" required placeholder="Nhập địa chỉ">
        </div>

        <div class="form-group">
            <label for="phone">Số Điện Thoại:</label>
            <input type="text" id="phone" name="phone" required placeholder="Nhập số điện thoại">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Nhập email">
        </div>

        <div class="form-group">
            <label for="status">Trạng thái:</label><br>
            <select id="status" name="status" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Bị khóa">Bị khóa</option>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" value="Thêm Khách Hàng">
        </div>
    </form>
</body>
</html>
