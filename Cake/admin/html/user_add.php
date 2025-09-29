<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['Fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['Email']);
    $password = mysqli_real_escape_string($conn, $_POST['Password']);
    $phone = mysqli_real_escape_string($conn, $_POST['PhoneNumber']);
    $address = mysqli_real_escape_string($conn, $_POST['Address']);
    $hashedPassword = md5($password); // Mã hóa mật khẩu bằng MD5

    // Xử lý upload hình ảnh
    $image = '';
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
        $image = basename($_FILES['Image']['name']);
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $image;
        move_uploaded_file($_FILES['Image']['tmp_name'], $targetPath);
    }

    // Thêm tài khoản vào cơ sở dữ liệu
    $query = "INSERT INTO Customers (Fullname, Email, Password, PhoneNumber, Address, Image, Status) 
              VALUES ('$fullname', '$email', '$hashedPassword', '$phone', '$address', '$image', 1)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Thêm tài khoản thành công!'); window.location.href = 'user_list.php';</script>";
    } else {
        echo "<script>alert('Thêm tài khoản thất bại!'); window.location.href = 'user_list.php';</script>";
    }
}
?>