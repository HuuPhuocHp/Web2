<?php
// Kết nối cơ sở dữ liệu
include 'db_connect.php';

// Kiểm tra nếu có ID khách hàng được truyền từ URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn lấy thông tin khách hàng dựa trên ID
    $query = "SELECT * FROM customers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        echo "Khách hàng không tồn tại.";
        exit();
    }
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name']; 
    $address = $_POST['address']; 
    $phone = $_POST['phone']; 
    $email = $_POST['email']; 

    // Cập nhật thông tin khách hàng
    $updateQuery = "UPDATE customers SET name = ?, address = ?, phone_number = ?, email = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssssi", $name, $address, $phone, $email, $id);

    if ($updateStmt->execute()) {
        // Chuyển hướng về trang quản lý khách hàng sau khi cập nhật thành công
        header("Location: QLKH.php"); 
        exit();
    } else {
        echo "Có lỗi xảy ra khi cập nhật thông tin khách hàng.";
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
        /* Căn giữa form */
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Các trường input */
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Tiêu đề của form */
        h1 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        /* Nút cập nhật */
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        /* Nút cập nhật khi hover */
        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Các label */
        label {
            font-size: 18px;
            color: #333;
        }
    </style>
    <div class="container">
        <h1>Chỉnh Sửa Thông Tin Khách Hàng</h1>
        <form method="POST">
            <label for="name">Tên Khách Hàng:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
            <br>

            <label for="address">Địa Chỉ:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>" required>
            <br>

            <label for="phone">Số Điện Thoại:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($customer['phone_number']); ?>" required>
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            <br>
            

            <button type="submit">Cập Nhật</button>
        </form>
    </div>
</body>

</html>
 <!-- #region -->