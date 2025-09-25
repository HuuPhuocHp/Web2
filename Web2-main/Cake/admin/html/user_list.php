<?php
include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/header.php');
include($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/navbar.php");
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM Customers WHERE Fullname LIKE '%$search%' OR Email LIKE '%$search%' OR PhoneNumber LIKE '%$search%' OR Address LIKE '%$search%'";
} else {
    $query = "SELECT * FROM Customers";
}
$Customers = mysqli_query($conn, $query);
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<style>
  .btn-primary {
    background-color: #696cff !important; /* Đổi màu nền */
    border-color: #696cff !important;    /* Đổi màu viền */
  }
  a {
    text-decoration: none; /* Xóa gạch dưới */
  }

  a:hover {
    text-decoration: none; /* Xóa gạch dưới khi hover */
  }
  .card.mb-4 {
    margin-bottom: 20px;
  }

  .pagination {
    margin-top: 20px;
  }

  .pagination .page-item .page-link {
    color: #696cff; /* Màu sắc cho phân trang */
  }

  .pagination .page-item.active .page-link {
    background-color: #696cff;
    border-color: #696cff;
    color: white;
  }
  .search-bar {
    width: 600px; /* Đặt chiều rộng cho thanh tìm kiếm */
  }

  .search-button {
    width: 100px; /* Đặt chiều rộng cho nút tìm kiếm */
  }

  .search-container {
    display: flex; /* Đảm bảo thanh tìm kiếm và nút nằm trên cùng một hàng */
    align-items: center; /* Căn giữa theo chiều dọc */
  }
</style>

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
          <form action="user_list.php" method="GET" class="d-flex">
            <input type="text" class="form-control border-0 shadow-none search-bar" name="search" placeholder="Search..." aria-label="Search..." value="<?php echo htmlspecialchars($search); ?>" />
            <button type="submit" class="btn btn-primary ms-2 search-button">Tìm kiếm</button>
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

  <div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
      <h4 class="fw-bold py-3 mb-4">Danh sách người dùng</h4>

      <!-- Basic Bootstrap Table -->
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table" style="text-align: center">
            <thead>
              <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Hình ảnh</th>
                <th>Thời gian đăng nhập</th>
                <th>Thời gian đăng xuất</th>
                <th>Chức năng</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              <?php
              foreach ($Customers as $key => $value) : ?>
                <tr>
                  <td><?php echo $key + 1 ?></td>
                  <td><?php echo $value['Fullname'] ?></td>
                  <td><?php echo $value['Email'] ?></td>
                  <td><?php echo $value['PhoneNumber'] ?></td>
                  <td><?php echo $value['Address'] ?></td>
                  <td>
                    <img src="..//uploads//<?php echo $value['Image'] ?>" alt="" width="100">
                  </td>
                  <td><?php echo $value['Date_Login']?></td>
                  <td><?php echo $value['Date_Logout']?></td>
                  <td>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $value['CustomerId']; ?>">
                        Sửa
                    </button>
                    <button type="button" class="btn btn-danger">
                        <a style="color: white" href="user_delete.php?id=<?php echo $value['CustomerId']; ?>" onclick="return confirm('Bạn có chắc chắn xóa người dùng này?')">Xóa</a>
                    </button>
                    <?php if ($value['Status'] == 1): ?>
                        <button type="button" class="btn btn-secondary">
                            <a style="color: white" href="user_lock.php?id=<?php echo $value['CustomerId']; ?>" onclick="return confirm('Bạn có chắc chắn khóa người dùng này?')">Khóa</a>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-success">
                            <a style="color: white" href="user_unlock.php?id=<?php echo $value['CustomerId']; ?>" onclick="return confirm('Bạn có chắc chắn mở khóa người dùng này?')">Mở khóa</a>
                        </button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-body">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Thêm tài khoản người dùng
          </button>
        </div>
      </div>
      <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addUserModalLabel">Thêm tài khoản người dùng</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="user_add.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="fullname" class="form-label">Họ và tên</label>
                  <input type="text" class="form-control" id="fullname" name="Fullname" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="Email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Mật khẩu</label>
                  <input type="password" class="form-control" id="password" name="Password" required>
                </div>
                <div class="mb-3">
                  <label for="phone" class="form-label">Số điện thoại</label>
                  <input type="text" class="form-control" id="phone" name="PhoneNumber" required>
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">Địa chỉ</label>
                  <input type="text" class="form-control" id="address" name="Address" required>
                </div>
                <div class="mb-3">
                  <label for="image" class="form-label">Hình ảnh</label>
                  <input type="file" class="form-control" id="image" name="Image">
                </div>
                <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php foreach ($Customers as $key => $value) : ?>
<div class="modal fade" id="editUserModal<?php echo $value['CustomerId']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo $value['CustomerId']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel<?php echo $value['CustomerId']; ?>">Sửa thông tin người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="user_edit.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="CustomerId" value="<?php echo $value['CustomerId']; ?>">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="fullname" name="Fullname" value="<?php echo $value['Fullname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="Email" value="<?php echo $value['Email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="PhoneNumber" value="<?php echo $value['PhoneNumber']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="Address" value="<?php echo $value['Address']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="image" name="Image">
                        <img src="../uploads/<?php echo $value['Image']; ?>" alt="User Image" width="100" class="mt-2">
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
  </div>
  <?php
  include($_SERVER["DOCUMENT_ROOT"] . '/admin/inc/footer.php');
  ?>