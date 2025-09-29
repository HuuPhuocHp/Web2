<?php
include($_SERVER['DOCUMENT_ROOT'] . "/database/connect.php");

if (isset($_GET['customer_id']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $customer_id = $_GET['customer_id'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];

    // Truy vấn danh sách đơn hàng của khách hàng
    $query = "
        SELECT o.OderId, o.order_date, o.total_price
        FROM oders o
        WHERE o.CustomerId = '$customer_id' AND o.order_date BETWEEN '$start_date' AND '$end_date'
        ORDER BY o.order_date DESC
    ";
    $result = mysqli_query($conn, $query);

    // Hiển thị danh sách đơn hàng
    echo "<h3>Danh sách đơn hàng của khách hàng</h3>";
    echo "<table class='table table-bordered'>";
    echo "<thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Chi tiết</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['OderId']}</td>
                <td>{$row['order_date']}</td>
                <td>" . number_format($row['total_price'], 2) . " USD</td>
                <td><a href='order_detail.php?id={$row['OderId']}' class='btn btn-info'>Xem chi tiết</a></td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
}
?>