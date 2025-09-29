<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['id'])) {
    $ProductId = $_GET['id'];

    // Kiểm tra xem sản phẩm đã được bán ra hay chưa
    $check_query = "SELECT SUM(Quantity) AS TotalSold FROM OrderDetails WHERE ProductId = '$ProductId'";
    $check_result = mysqli_query($conn, $check_query);
    $check_row = mysqli_fetch_assoc($check_result);
    $total_sold = $check_row['TotalSold'];

    if ($total_sold > 0) {
        // Nếu sản phẩm đã được bán, chỉ ẩn sản phẩm
        $update_query = "UPDATE Products SET Status = 0 WHERE ProductId = '$ProductId'";
        if (mysqli_query($conn, $update_query)) {
            echo '<script>alert("Sản phẩm đã được bán, chỉ ẩn khỏi danh sách hiển thị!"); window.location.href = "product_list.php";</script>';
        } else {
            echo '<script>alert("Ẩn sản phẩm thất bại!"); window.location.href = "product_list.php";</script>';
        }
    } else {
        // Nếu sản phẩm chưa được bán, hỏi lại người dùng trước khi xóa
        echo '<script>
            if (confirm("Sản phẩm chưa được bán. Bạn có chắc chắn muốn xóa sản phẩm này?")) {
                window.location.href = "product_delete_confirm.php?id=' . $ProductId . '";
            } else {
                window.location.href = "product_list.php";
            }
        </script>';
    }
}
?>
