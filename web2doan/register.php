<?php
session_start();
$tb = ""; // Biến thông báo lỗi

// Kiểm tra nếu người dùng nhấn nút ĐĂNG KÝ
if (isset($_POST["register"])) {
    $uname = trim($_POST["uname"]);
    $psw = trim($_POST["psw"]);

    // Kết nối CSDL
    $conn = new mysqli("localhost", "root", "", "test"); // Đảm bảo "test" là cơ sở dữ liệu đúng

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Lỗi kết nối: " . $conn->connect_error);
    }

    // Kiểm tra xem tên đăng nhập đã tồn tại chưa
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $tb = "Tên đăng nhập đã tồn tại!";
    } else {
        // Thêm người dùng mới vào CSDL
        $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $uname, $psw);
        if ($stmt->execute()) {
            $tb = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            // Chuyển hướng về trang đăng nhập sau khi đăng ký thành công
            header("Location: login.php");  // Chuyển hướng về trang đăng nhập
            exit();  // Dừng chương trình để không thực hiện mã sau khi chuyển hướng
        } else {
            $tb = "Đăng ký không thành công!";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://mir-s3-cdn-cf.behance.net/project_modules/fs/117cc8147434521.62c2d2c39cc1b.png') no-repeat center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }       
        .boxcenter {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .imgcontainer {
            margin-bottom: 20px;
        }
        .avatar {
            width: 80px;
            border-radius: 50%;
        }
        .container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
        .link {
            margin-top: 10px;
            display: block;
            color: #007bff;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="boxcenter">
        <h2>Đăng ký tài khoản</h2>
        <form action="" method="post">
            <div class="container">
                <label for="uname"><b>Tên đăng nhập</b></label>
                <input type="text" placeholder="Nhập tên đăng nhập" name="uname" required>

                <label for="psw"><b>Mật khẩu</b></label>
                <input type="password" placeholder="Nhập mật khẩu" name="psw" required>

                <?php if ($tb !== ""): ?>
                    <div class="error-message"><?= $tb ?></div>
                <?php endif; ?>

                <button type="submit" name="register">ĐĂNG KÝ</button>
            </div>
        </form>
    </div>
</body>
</html>
