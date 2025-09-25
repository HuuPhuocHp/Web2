<?php
  include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/header.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/navbar.php");
  include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");


  $query = "SELECT Count(*) FROM category where status = 1";
  $category = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($category);
  $quantity_category = implode($data);

  $query1 = "SELECT Count(*) FROM brands  where Status = 1";
  $brands = mysqli_query($conn, $query1);
  $data = mysqli_fetch_assoc($brands);
  $quantity_brands = implode($data);

  $query2 = "SELECT Count(*) FROM customers  where Status = 1";
  $customers = mysqli_query($conn, $query2);
  $data = mysqli_fetch_assoc($customers);
  $quantity_customers = implode($data);

  $query3 = "SELECT Count(*) FROM contacts";
  $contacts = mysqli_query($conn, $query3);
  $data = mysqli_fetch_assoc($contacts);
  $quantity_contacts = implode($data);

  $query4 = "SELECT Count(*) FROM oders where status = 0 order by order_date DESC";
  $orders = mysqli_query($conn, $query4);
  $data = mysqli_fetch_assoc($orders);
  $quantity_orders = implode($data);

  $query5 = "SELECT Count(*) FROM products where status = 1 and is_accept = 1";
  $products = mysqli_query($conn, $query5);
  $data = mysqli_fetch_assoc($products);
  $quantity_products = implode($data);


$search = '';
$start_date = '';
$end_date = '';

if (isset($_GET['search']) || isset($_GET['start_date']) || isset($_GET['end_date'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

    // Xây dựng điều kiện lọc
    $conditions = "o.CustomerId = c.CustomerId AND o.status != 1";
    if (!empty($search)) {
        $conditions .= " AND (c.Fullname LIKE '%$search%' OR o.address LIKE '%$search%' OR o.number_phone LIKE '%$search%')";
    }
    if (!empty($start_date)) {
        $conditions .= " AND o.order_date >= '$start_date'";
    }
    if (!empty($end_date)) {
        $conditions .= " AND o.order_date <= '$end_date'";
    }

    // Truy vấn với điều kiện lọc
    $query = "SELECT o.OderId, o.number_phone, o.order_date, o.note, o.address, c.Fullname, o.total_price, o.status
              FROM oders o, Customers c 
              WHERE $conditions
              ORDER BY o.order_date DESC";
} else {
    // Truy vấn mặc định
    $query = "SELECT o.OderId, o.number_phone, o.order_date, o.note, o.address, c.Fullname, o.total_price, o.status
              FROM oders o, Customers c 
              WHERE o.CustomerId = c.CustomerId 
              AND o.status != 1
              ORDER BY o.total_price DESC";
}

$Orders = mysqli_query($conn, $query);

?>
<body>
<?php
 include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/navbar.php");
?>
      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center search-container">
                <form action="index.php" method="GET" class="d-flex">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input type="text" class="form-control border-0 shadow-none search-bar" name="search" placeholder="Search..." aria-label="Search..." value="<?php echo htmlspecialchars($search); ?>" />
                  
                  <!-- Lọc theo thời gian -->
                  <input type="date" class="form-control" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" placeholder="Ngày bắt đầu">
                  <input type="date" class="form-control" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" placeholder="Ngày kết thúc">
                  
                  <button type="submit" class="btn btn-primary search-button">Tìm kiếm</button>
                </form>
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              <li class="nav-item lh-1 me-3">
              </li>

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block"><?php echo session::get('Username') ?></span>
                          <small class="text-muted">Admin</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="d-flex align-items-center align-middle">
                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                        <span class="flex-grow-1 align-middle">Billing</span>
                        <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                      </span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="?action=logout">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-lg-8 mb-4 order-0">
        <div class="card">
          <div class="d-flex align-items-end row">
            <div class="col-sm-7">
              <div class="card-body">
                <h5 class="card-title text-primary">Sản phẩm mới🎉</h5>
                <p class="mb-4">
                 Số lượng: <?php echo $quantity_products  ?>
                </p>
                <a href="product_list.php" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
              </div>
            </div>
            <div class="col-sm-5 text-center text-sm-left">
              <div class="card-body pb-0 px-0 px-md-4">
                <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
          <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <h3>Người Dùng</h3>
                <p class="card-title text-nowrap mb-1">Số lượng: <?php echo $quantity_customers ?> </p>
                <a href="user_list.php" class="mt-2 btn btn-sm btn-outline-primary">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <h3>Phản hồi</h3>
                <p class="card-title text-nowrap mb-1">Số lượng: <?php echo $quantity_contacts ?> </p>
                <a href="contact_list.php" class="mt-2 btn btn-sm btn-outline-primary">Xem chi tiết</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Revenue -->
      <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
          <div class="row row-bordered g-0">
            <div class="col-md-12">
              <h5 class="card-header m-0 me-2 pb-3">Thống kê</h5>
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
                    <?php
                    foreach ($Orders as $key => $value) : ?>
                      <tr>
                        <td><?php echo $key + 1 ?></td>
                        <td><?php echo $value['Fullname'] ?></td>
                        <td><?php echo $value['total_price'] ?></td>
                        <td><?php echo $value['order_date'] ?></td>
                        <td>
                          <?php if ($value['status'] == 0) { ?>
                              <span class="label bg-red">Chưa xử lý</span>
                          <?php } else if ($value['status'] == 2) { ?>
                              <span class="label bg-green">Đã xử lý</span>

                          <?php } else if ($value['status'] == 3) { ?>
                              <span class="label bg-blue">Đã giao hàng</span>
                          <?php } ?>
                        </td>
                        <td>
                          <button type="button" class="btn btn-primary">
                            <a style="color: white" ; href="order_detail.php?id=<?php echo $value['OderId'] ?>">Chi tiết</a>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
            </tbody>
          </table>
            </div>
          </div>
        </div>
      </div>
      <!--/ Total Revenue -->
      <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                </div>
                <h3>Thương Hiệu</h3>
                <p class="card-title text-nowrap mb-1">Số lượng: <?php echo $quantity_brands ?> </p>
                <a href="brand_list.php" class="mt-2 btn btn-sm btn-outline-primary">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <div class="col-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                  </div>
                  <h3>Loại Bánh</h3>
                  <p class="card-title text-nowrap mb-1">Số lượng: <?php echo $quantity_category ?> </p>
                  <a href="category_list.php" class="mt-2 btn btn-sm btn-outline-primary">Xem chi tiết</a>
              </div>
            </div>
          </div>
          <!-- </div>
    <div class="row"> -->
          <div class="col-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                  <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                    <h3>Số đơn hàng chưa xử lý  </h3>
                    <p class="card-title text-nowrap mb-1">Số lượng: <?php echo $quantity_orders ?> </p>
                    <a href="order_list.php" class="mt-2 btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / Content -->
  <!-- Footer -->

<!-- Content wrapper -->
<?php
include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/footer.php');
?>
<style>
  .search-bar {
    width: 600px; /* Đặt chiều rộng cho thanh tìm kiếm */
  }

  .search-button {
    width: 120px; /* Đặt chiều rộng cho nút tìm kiếm */
    text-align: center; /* Căn giữa chữ trong nút */
  }

  .search-container {
    display: flex; /* Đảm bảo thanh tìm kiếm và nút nằm trên cùng một hàng */
    align-items: center; /* Căn giữa theo chiều dọc */
    gap: 10px; /* Khoảng cách giữa thanh tìm kiếm và nút */
  }

  .search-container input[type="date"] {
    width: 200px;
    margin-left: 10px;
  }

  .search-container .search-button {
    margin-left: 10px;
  }
</style>