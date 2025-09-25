<?php
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

$message = ""; // Biến để lưu thông báo

// Hàm xử lý sản phẩm
function addProduct($conn, $data, $file) {
    // Kiểm tra các điều kiện hợp lệ
    if ($data['buy_price'] < 0 || $data['sell_price'] < 0) {
        return "Giá sản phẩm không được là số âm.";
    } else if ($data['quantity'] < 0 || $data['avaiable_quantity'] < 0) {
        return "Số lượng sản phẩm không được là số âm.";
    } else if ($data['avaiable_quantity'] > $data['quantity']) {
        return "Số lượng bán không được lớn hơn số lượng nhập.";
    } 

    // Kiểm tra tên sản phẩm trùng lặp
    $query = "SELECT * FROM Products WHERE Name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $data['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return "Tên sản phẩm đã tồn tại.";
    }

    // Xử lý file hình ảnh
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $div = explode('.', $file_name);
    $file_ext = strtolower(end($div));
    $unique_image = substr(md5(time()), 0, 10) . '.' . $file_ext;
    move_uploaded_file($file_tmp, "../uploads/" . $unique_image);

    // Thêm sản phẩm vào cơ sở dữ liệu
    $query = "INSERT INTO Products (Name, Image, Quantity, Avaiable_quantity, Description, BuyPrice, SellPrice, Status, CategoriId, BrandId) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiissddii", 
        $data['name'], 
        $unique_image, 
        $data['quantity'], 
        $data['avaiable_quantity'], 
        $data['description'], 
        $data['buy_price'], 
        $data['sell_price'], 
        $data['status'], 
        $data['id_categories'], 
        $data['id_brands']
    );

    if ($stmt->execute()) {
        echo "<script>setTimeout(function() { window.location.href = 'product_list.php'; }, 2000);</script>";
        return "Thêm sản phẩm thành công!";
    } else {
        return "Thêm sản phẩm không thành công.";
    }
}

if (isset($_POST['submit'])) {
    $data = [
        'name' => $_POST['name'],
        'id_brands' => $_POST['id_brands'],
        'id_categories' => $_POST['id_categories'],
        'buy_price' => $_POST['buy_price'],
        'sell_price' => $_POST['sell_price'],
        'quantity' => $_POST['quantity'],
        'avaiable_quantity' => $_POST['avaiable_quantity'],
        'status' => $_POST['status'],
        'description' => $_POST['description']
    ];

    $message = addProduct($conn, $data, $_FILES['image']);
}
?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Thêm sản phẩm mới</h4>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Hiển thị thông báo -->
                        <div class="mb-3">
                            <?php if (!empty($message)) { ?>
                                <div class="alert alert-info"><?php echo $message; ?></div>
                            <?php } ?>
                        </div>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label" for="name">Tên sản phẩm</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Bánh kem Le Castella" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thương hiệu</label>
                                <select name="id_brands" class="form-control">
                                    <option value="">--------------Loại thương hiệu--------------</option>
                                    <?php 
                                    $sql2 = "SELECT * FROM brands WHERE status = 1";
                                    $brands = mysqli_query($conn, $sql2);
                                    foreach ($brands as $value) { ?>
                                        <option value="<?php echo $value["BrandId"] ?>">
                                            <?php echo $value["BrandName"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Loại bánh</label>
                                <select name="id_categories" class="form-control">
                                    <option value="">--------------Loại bánh--------------</option>
                                    <?php 
                                    $sql1 = "SELECT * FROM category where status = 1";
                                    $categorys = mysqli_query($conn, $sql1);
                                    foreach ($categorys as $value) { ?>
                                        <option value="<?php echo $value["CategoryId"] ?>">
                                            <?php echo $value["CategoryName"] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="image">Hình ảnh sản phẩm</label>
                                <input type="file" class="form-control" id="image" name="image" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="buy_price">Giá nhập</label>
                                <input type="number" class="form-control" id="buy_price" name="buy_price" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="sell_price">Giá bán</label>
                                <input type="number" class="form-control" id="sell_price" name="sell_price" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="quantity">Số lượng nhập</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="avaiable_quantity">Số lượng bán</label>
                                <input type="number" class="form-control" id="avaiable_quantity" name="avaiable_quantity"required />
                            </div>      
                            <div class="mb-3">
                                <label class="form-label" for="description">Mô tả</label>
                                <textarea type="text" class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>                                            
                            <div class="mb-3">
                                <label class="form-label" for="status">Trạng thái</label><br>
                                <label>
                                    <input type="radio" name="status" id="status" value="1" checked> Hiện
                                </label><br>
                                <label>
                                    <input type="radio" name="status" id="status" value="0"> Ẩn
                                </label>
                            </div>
                            <button type="submit" name="submit" class="btn btn-success mt-4">Thêm sản phẩm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
