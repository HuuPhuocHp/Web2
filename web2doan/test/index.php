<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chính</title>
</head>
<body>
    <h1>Chào mừng, <?php echo $_SESSION["user"]["name"]; ?>!</h1>
    <a href="logout.php">Đăng xuất</a>
</body>
</html>
