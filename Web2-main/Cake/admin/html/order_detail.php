<?php
ob_start();
include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/header.php');
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/navbar.php");
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/admin/html/order_model.php");

$orderModel = new OrderModel($conn); // Khởi tạo đối tượng OrderModel

$message = ""; // Biến thông báo mặc định
$update_result = null;

if (isset($_GET['id'])) {
    $id_order = $_GET['id'];

    // Lấy thông tin đơn hàng
    $order = $orderModel->getOrderDetails($id_order);

    if ($order) {
        // Lấy thông tin khách hàng
        $customer = $orderModel->getCustomerInfo($order['CustomerId']);

        // Lấy thông tin sản phẩm trong đơn hàng
        $products_result = $orderModel->getProductsInOrder($id_order);
        $products = mysqli_fetch_all($products_result, MYSQLI_ASSOC);

        // Lấy trạng thái hiện tại của đơn hàng
        $current_status = $order['status'];

        // Xử lý cập nhật trạng thái đơn hàng
        if (isset($_POST['submit'])) {
            $status = $_POST['status'];
            $update_result = $orderModel->updateOrderStatus($id_order, $status);
            if ($update_result) {
                $message = "Cập nhật trạng thái đơn hàng thành công.";
                echo "<script>setTimeout(function() { window.location.href = 'order_list.php'; }, 2000);</script>";
            } else {
                $message = "Xảy ra lỗi khi cập nhật trạng thái.";
            }
        }
    }
}

$productId = $_GET['id']; // Lấy ProductId từ URL

// Xóa các hàng liên quan trong bảng orderdetails
$deleteOrderDetailsQuery = "DELETE FROM orderdetails WHERE ProductId = ?";
$stmt = $conn->prepare($deleteOrderDetailsQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();

// Sau đó xóa sản phẩm trong bảng products
$deleteProductQuery = "DELETE FROM products WHERE ProductId = ?";
$stmt = $conn->prepare($deleteProductQuery);
$stmt->bind_param("i", $productId);
$stmt->execute();

echo "Sản phẩm đã được xóa thành công!";
?>

<div class="layout-page">
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <!-- Navbar code remains unchanged -->
    </nav>

    <div class="content-wrapper">
        <!-- Hiển thị thông báo -->
        <?php if (!empty($message)) : ?>
            <div class="container-xxl mt-3">
                <div class="alert alert-<?php echo ($update_result) ? 'success' : 'danger'; ?>" role="alert">
                    <?php echo $message; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Thông tin khách hàng</h3>
                    </div>
                    <div class="panel-body text-left">
                        <p>Tên khách hàng: <?php echo $customer['Fullname'] ?></p>
                        <p>Số điện thoại: <?php echo $order['number_phone'] ?></p>
                        <p>Địa chỉ nhận hàng: <?php echo $order['address'] ?></p>
                        <p>Ngày đặt hàng: <?php echo $order['order_date'] ?></p>
                        <p>Ghi chú của khách hàng: <?php echo $order['Note'] ?> </p>
                        <p>Trạng thái đơn hàng:
                            <?php if ($order['status'] == 0) { ?>
                                Chưa xử lý
                            <?php } else if ($order['status'] == 1) { ?>
                                Đã Hủy
                            <?php } else if ($order['status'] == 2) { ?>
                                Đã xử lý
                            <?php } else if ($order['status'] == 3) { ?>
                                Đã giao hàng
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Basic Bootstrap Table -->
            <div class="panel-heading">
                <h3 class="panel-title">Thông tin chi tiết đơn hàng</h3>
            </div>
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table" style="text-align: center">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Số lượng</th>
                                <th>Giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php
                            $total_price = 0;
                            foreach ($products as $key => $value) : 
                                $total_price += $value['Price'] * $value['Quantity']; ?>
                                <tr>
                                    <td><?php echo $key + 1 ?></td>
                                    <td><?php echo $value['Name'] ?></td>
                                    <td>
                                        <img src="../uploads/<?php echo $value['Image'] ?>" alt="" width="100">
                                    </td>
                                    <td><?php echo $value['Quantity'] ?></td>
                                    <td><?php echo $value['Price'] ?></td>
                                    <td><?php echo $value['Quantity'] * $value['Price'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Tổng tiền:</strong></td>
                                <td><?php echo $total_price ?> USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form cập nhật trạng thái -->
            <form method="POST">
                <div class="form-group mt-4">
                    <select name="status" id="status" required>
                        <option value="0" <?php echo ($current_status == 0) ? 'selected' : ''; ?>>Chưa xử lý</option>
                        <option value="1" <?php echo ($current_status == 1) ? 'selected' : ''; ?>>Đã Hủy</option>
                        <option value="2" <?php echo ($current_status == 2) ? 'selected' : ''; ?>>Đã xử lý</option>
                        <option value="3" <?php echo ($current_status == 3) ? 'selected' : ''; ?>>Đã giao hàng</option>
                    </select>
                </div>
                <button class="mt-4 btn btn-primary" type="submit" name="submit">Cập nhật</button>
            </form>
        </div>
    </div>
</div>

<?php
include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/footer.php');
?>
