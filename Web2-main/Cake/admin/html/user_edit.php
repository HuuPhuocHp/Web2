<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $CustomerId = $_POST['CustomerId'];
    $Fullname = mysqli_real_escape_string($conn, $_POST['Fullname']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $PhoneNumber = mysqli_real_escape_string($conn, $_POST['PhoneNumber']);
    $Address = mysqli_real_escape_string($conn, $_POST['Address']);

    // Xử lý hình ảnh
    if (!empty($_FILES['Image']['name'])) {
        $image = $_FILES['Image']['name'];
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['Image']['tmp_name'], $target);

        $query = "UPDATE Customers SET Fullname='$Fullname', Email='$Email', PhoneNumber='$PhoneNumber', Address='$Address', Image='$image' WHERE CustomerId='$CustomerId'";
    } else {
        $query = "UPDATE Customers SET Fullname='$Fullname', Email='$Email', PhoneNumber='$PhoneNumber', Address='$Address' WHERE CustomerId='$CustomerId'";
    }

    if (mysqli_query($conn, $query)) {
        echo '<script>alert("Cập nhật thông tin thành công!"); window.location.href = "user_list.php";</script>';
    } else {
        echo '<script>alert("Cập nhật thông tin thất bại!"); window.location.href = "user_list.php";</script>';
    }
}
?>