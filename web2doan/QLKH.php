<?php
// Kết nối cơ sở dữ liệu
include 'db_connect.php';

// Truy vấn để lấy tất cả khách hàng
$query = "SELECT * FROM customers";
$result = $conn->query($query);
?>

<!DOCTYPE html>
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
                    <li><a href="QLSP.php">Quản lý sản phẩm</a></li>
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
    <!-- Nội dung chính -->
    <div class="container">
        <h1>Quản Lý Khách Hàng</h1>
        <div class="table-container">
            <table class="table" id="customer-table">
                <thead>
                    <tr>
                        <th>Tên Khách Hàng</th>
                        <th>Địa Chỉ</th>
                        <th>Số Điện Thoại</th>
                        <th>Email</th>
                        <th>Ngày sinh</th>
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Nếu có khách hàng, hiển thị dữ liệu
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['address'] . "</td>";
                            echo "<td>" . $row['phone_number'] . "</td>"; // Kiểm tra xem trường này có tồn tại trong cơ sở dữ liệu
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['registration_date'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td><a href='edit_user.php?id=" . $row['id'] . "' class='action-btn edit'>Sửa</a>
                                      <a href='delete_user.php?id=" . $row['id'] . "' class='action-btn delete' onclick='return confirm(\"Bạn có chắc chắn muốn xóa khách hàng này?\")'>Xóa</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Không có khách hàng nào.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="pagination-container" id="pagination">
                <button class="pagination-btn" onclick="goToPage(1)">1</button>
                <button class="pagination-btn" onclick="goToPage(2)">2</button>
                <button class="pagination-btn" onclick="goToPage(3)">3</button>
                <span class="pagination-btn">...</span>
                <button class="pagination-btn" onclick="goToPage(6)">6</button>
            </div>
        </div>

        <div class="add-btn-container">
            <a href="add_user.php" class="action-btn add"><i class="fas fa-user-plus"></i> Thêm Khách Hàng</a>
        </div>
    </>

    <script>
        let currentPage = 1;

        function goToPage(pageNumber) {
            currentPage = pageNumber;
            const rows = document.querySelectorAll('#customer-table tbody tr');
            const rowsPerPage = 5;
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = 'none';
            });

            for (let i = startIndex; i < endIndex && i < rows.length; i++) {
                rows[i].style.display = 'table-row';
            }

            updatePagination();
        }

        function updatePagination() {
            const buttons = document.querySelectorAll('.pagination-btn');
            buttons.forEach((button) => {
                if (parseInt(button.textContent) === currentPage) {
                    button.disabled = true;
                } else {
                    button.disabled = false;
                }
            });
        }

        goToPage(1);
    </script>
