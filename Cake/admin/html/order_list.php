<?php
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/header.php");
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/navbar.php");
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");
include($_SERVER['DOCUMENT_ROOT'] . "/admin/html/order_model.php");

$orderModel = new OrderModel($conn); // Tạo đối tượng OrderModel

// Xử lý phân trang
$limit = 5;
$cr_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$start = ($cr_page - 1) * $limit;

$where = [];

// Lọc theo tình trạng đơn hàng
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $where[] = "o.status = '$status'"; // Thêm tiền tố 'o.' để chỉ định bảng 'oders'
}

// Lọc theo khoảng thời gian
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
    $where[] = "o.order_date BETWEEN '$start_date' AND '$end_date'"; // Thêm tiền tố 'o.' cho 'order_date'
}

// Lọc theo địa điểm giao hàng
if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $where[] = "o.address LIKE '%$location%'"; // Thêm tiền tố 'o.' cho 'delivery_address'
}

// Kết hợp các điều kiện lọc
$where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Truy vấn danh sách đơn hàng
$query = "SELECT o.*, c.Fullname 
          FROM oders o 
          JOIN Customers c ON o.CustomerId = c.CustomerId 
          $where_sql 
          LIMIT $start, $limit";
$orders = mysqli_query($conn, $query);

// Truy vấn tổng số đơn hàng (phục vụ phân trang)
$total_query = "SELECT COUNT(*) as total 
                FROM oders o 
                JOIN Customers c ON o.CustomerId = c.CustomerId 
                $where_sql";
$total_result = mysqli_query($conn, $total_query);
$totalData = mysqli_fetch_assoc($total_result);
$total = $totalData['total'];
$page = ceil($total / $limit);
?>

<div class="layout-page">
  <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>
  </nav>

  <div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4">Danh sách đơn đặt hàng</h4>
      
      <form action="order_list.php" method="GET" class="form-inline mb-4">
        <div class="row">
          <!-- Lọc theo tình trạng đơn hàng -->
          <div class="col-md-3">
            <label for="status">Tình trạng đơn hàng</label>
            <select name="status" id="status" class="form-control">
              <option value="">Tất cả</option>
              <option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>Chưa xử lý</option>
              <option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == '1') ? 'selected' : ''; ?>>Đã Hủy</option>
              <option value="2" <?php echo (isset($_GET['status']) && $_GET['status'] == '2') ? 'selected' : ''; ?>>Đã xử lý</option>
              <option value="3" <?php echo (isset($_GET['status']) && $_GET['status'] == '3') ? 'selected' : ''; ?>>Đã giao hàng</option>
            </select>
          </div>

          <!-- Lọc theo khoảng thời gian -->
          <div class="col-md-3">
            <label for="start_date">Từ ngày</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
          </div>
          <div class="col-md-3">
            <label for="end_date">Đến ngày</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
          </div>

          <!-- Lọc theo địa điểm giao hàng -->
          <div class="col-md-3">
            <label for="location">Địa điểm giao hàng</label>
            <input type="text" name="location" id="location" class="form-control" placeholder="Nhập quận/huyện/thành phố" value="<?php echo isset($_GET['location']) ? $_GET['location'] : ''; ?>">
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Lọc</button>
            <a href="order_list.php" class="btn btn-secondary">Xóa bộ lọc</a>
          </div>
        </div>
      </form>

      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table" style="text-align: center">
            <thead>
              <tr>
                <th>STT</th>
                <th>Tên khách hàng</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th>Chức năng</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <?php foreach ($orders as $key => $value): ?>
                <tr>
                  <td><?php echo $key + 1 ?></td>
                  <td><?php echo $value['Fullname'] ?></td>
                  <td><?php echo $value['total_price'] ?></td>
                  <td><?php echo $value['order_date'] ?></td>
                  <td>
                    <?php 
                      if ($value['status'] == 0) { echo "<span class='label bg-red'>Chưa xử lý</span>"; }
                      elseif ($value['status'] == 1) { echo "<span class='label bg-yellow'>Đã Hủy</span>"; }
                      elseif ($value['status'] == 2) { echo "<span class='label bg-blue'>Đã xử lý</span>"; }
                      elseif ($value['status'] == 3) { echo "<span class='label bg-green'>Đã giao hàng</span>"; }
                    ?>
                  </td>
                  <td>
                    <a href="order_detail.php?id=<?php echo $value['OderId'] ?>" class="btn btn-primary">Chi tiết</a>
                    <a href="order_delete.php?id=<?php echo $value['OderId'] ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn xóa ?')">Xóa</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php if ($page > 1) { ?>
        <hr>
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <?php
            if ($cr_page - 1 > 0) {
              echo "<li class='page-item'><a class='page-link' href='order_list.php?page=1'><i class='bx bx-chevrons-left'></i></a></li>";
              echo "<li class='page-item'><a class='page-link' href='order_list.php?page=".($cr_page - 1)."'><i class='bx bx-chevron-left'></i></a></li>";
            }

            for ($i = 1; $i <= $page; $i++) {
              $active = ($cr_page == $i) ? 'active' : '';
              echo "<li class='page-item $active'><a class='page-link' href='order_list.php?page=$i'>$i</a></li>";
            }

            if ($cr_page + 1 <= $page) {
              echo "<li class='page-item'><a class='page-link' href='order_list.php?page=".($cr_page + 1)."'><i class='bx bx-chevron-right'></i></a></li>";
              echo "<li class='page-item'><a class='page-link' href='order_list.php?page=$page'><i class='bx bx-chevrons-right'></i></a></li>";
            }
            ?>
          </ul>
        </nav>
      <?php } ?>
    </div>
  </div>
</div>

<?php include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/footer.php'); ?>


