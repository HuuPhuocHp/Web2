<?php
// Bắt đầu session
session_start();

// Hủy tất cả các session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng người dùng về trang đăng nhập (index.html)
header("Location: index.html");
exit();
?>
