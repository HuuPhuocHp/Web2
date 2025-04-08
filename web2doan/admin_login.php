<?php
session_start();

// Kiểm tra nếu người dùng nhấn nút ĐĂNG NHẬP ADMIN
if (isset($_POST["admin_login"])) {
    $psw = trim($_POST["psw"]);  // Lấy mật khẩu từ form

    // Kiểm tra nếu mật khẩu đúng
    if ($psw == "admin123") {
        $_SESSION['s_user'] = ['username' => 'admin', 'role' => 'admin'];
        header('Location: add.html'); // Chuyển hướng đến trang add.html nếu đăng nhập thành công
        exit();
    } else {
        $tb = "Sai mật khẩu!";  // Nếu mật khẩu sai, hiển thị thông báo
    }
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
        .container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input[type="password"] {
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
    </style>
</head>
<body>
    <div class="boxcenter">
        <h2>Đăng nhập Quản trị viên</h2>
        <form action="" method="post">
            <div class="container">
                <label for="uname"><b>Tên đăng nhập</b></label>
                <input type="text" placeholder="Nhập tên đăng nhập" name="uname" value="admin" readonly> <!-- Tên đăng nhập admin cố định -->

                <label for="psw"><b>Mật khẩu</b></label>
                <input type="password" placeholder="Nhập mật khẩu" name="psw" required>

                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (isset($tb)): ?>
                    <div class="error-message"><?= $tb ?></div>
                <?php endif; ?>

                <button type="submit" name="admin_login">ĐĂNG NHẬP ADMIN</button>
            </div>
        </form>
    </div>
</body>
</html>
