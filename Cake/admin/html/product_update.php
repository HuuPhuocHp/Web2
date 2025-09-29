<?php
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

$message = "";

// Hàm cập nhật sản phẩm
function updateProduct($conn, $id, $product, $name, $id_brands, $id_categories, $buy_price, $sell_price, $quantity, $avaiable_quantity, $status, $description) {
    $message = "";

    $query = "SELECT COUNT(*) FROM Products WHERE Name = ? AND ProductId != ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row();
    
    if ($result[0] > 0) {
        return "Tên sản phẩm đã tồn tại.";
    }

    // Kiểm tra dữ liệu đầu vào
    if (!is_numeric($buy_price) || !is_numeric($sell_price)) {
        return "Giá sản phẩm phải là số.";
    } else if (!is_numeric($quantity) || !is_numeric($avaiable_quantity)) {
        return "Số lượng sản phẩm phải là số.";
    } else if ($buy_price < 0 || $sell_price < 0 || $quantity < 0 || $avaiable_quantity < 0) {
        return "Giá và số lượng không được là số âm.";
    } else if ($avaiable_quantity > $quantity) {
        return "Số lượng bán không được lớn hơn số lượng nhập.";
    } else {
        // Xử lý hình ảnh
        $unique_image = $product['Image']; // Giữ nguyên hình ảnh cũ
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $unique_image = substr(md5(time()), 0, 10) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            move_uploaded_file($file_tmp, "../uploads/" . $unique_image);
        }

        // Thực hiện cập nhật sản phẩm
        $query = "UPDATE Products SET Name=?, Image=?, Quantity=?, Avaiable_quantity=?, Description=?, BuyPrice=?, SellPrice=?, Status=?, CategoriId=?, BrandId=? WHERE ProductId=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssiisddiiii", $name, $unique_image, $quantity, $avaiable_quantity, $description, $buy_price, $sell_price, $status, $id_categories, $id_brands, $id);

        if ($stmt->execute()) {
            echo "<script>setTimeout(function() { window.location.href = 'product_list.php'; }, 2000);</script>";
            return "Cập nhật sản phẩm thành công.";
        }
    }
}

// Lấy thông tin sản phẩm dựa trên ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM Products WHERE ProductId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu sản phẩm không tồn tại
    if ($result->num_rows == 0) {
        $message = "Sản phẩm không tồn tại.";
    } else {
        // Nếu tìm thấy sản phẩm
        $product = $result->fetch_assoc();
    }

    // Lấy danh sách danh mục và thương hiệu
    $categories = mysqli_query($conn, "SELECT * FROM category WHERE status = 1");
    $brands = mysqli_query($conn, "SELECT * FROM brands WHERE status = 1");
}

// Xử lý cập nhật sản phẩm khi form được submit
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $id_brands = $_POST['id_brands'];
    $id_categories = $_POST['id_categories'];
    $buy_price = $_POST['buy_price'];
    $sell_price = $_POST['sell_price'];
    $quantity = $_POST['quantity'];
    $avaiable_quantity = $_POST['avaiable_quantity'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    $message = updateProduct($conn, $id, $product, $name, $id_brands, $id_categories, $buy_price, $sell_price, $quantity, $avaiable_quantity, $status, $description);
}
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Cập nhật sản phẩm</h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Hiển thị thông báo -->
                        <?php if (!empty($message)) { ?>
                            <div class="alert alert-info"> <?php echo $message; ?> </div>
                        <?php } ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($product['Name']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thương hiệu</label>
                                <select name="id_brands" class="form-control">
                                    <?php while ($brand = mysqli_fetch_assoc($brands)) { ?>
                                        <option value="<?php echo $brand['BrandId']; ?>" <?php echo ($brand['BrandId'] == $product['BrandId']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($brand['BrandName']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại bánh</label>
                                <select name="id_categories" class="form-control">
                                    <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                                        <option value="<?php echo $category['CategoryId']; ?>" <?php echo ($category['CategoryId'] == $product['CategoriId']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['CategoryName']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" name="image" />
                                <img src="../uploads/<?php echo htmlspecialchars($product['Image']); ?>" alt="Hình ảnh sản phẩm" width="150" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá nhập</label>
                                <input type="text" class="form-control" name="buy_price" value="<?php echo htmlspecialchars($product['BuyPrice']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá bán</label>
                                <input type="text" class="form-control" name="sell_price" value="<?php echo htmlspecialchars($product['SellPrice']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng nhập</label>
                                <input type="text" class="form-control" name="quantity" value="<?php echo htmlspecialchars($product['Quantity']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng bán</label>
                                <input type="text" class="form-control" name="avaiable_quantity" value="<?php echo htmlspecialchars($product['Avaiable_quantity']); ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($product['Description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo ($product['Status'] == 1) ? 'selected' : ''; ?>>Hiện</option>
                                    <option value="0" <?php echo ($product['Status'] == 0) ? 'selected' : ''; ?>>Ẩn</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>