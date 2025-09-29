<?php
class OrderModel {
    private $conn;

    // Constructor để khởi tạo kết nối cơ sở dữ liệu
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Lấy danh sách đơn hàng
    public function getAllOrders($limit = 5, $start = 0) {
        $query = "SELECT o.OderId, o.number_phone, o.order_date, o.note, o.address, c.Fullname, o.total_price, o.status
                  FROM oders o, Customers c WHERE o.CustomerId = c.CustomerId ORDER BY total_price DESC LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $start, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetails($orderId) {
        $query = "SELECT * FROM oders WHERE OderId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($orderId, $status) {
        $query = "UPDATE oders SET status = ? WHERE OderId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $status, $orderId);
        return $stmt->execute();
    }

    // Xóa đơn hàng
    public function deleteOrder($orderId) {
        $query = "DELETE FROM oders WHERE OderId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }

    // Lấy thông tin khách hàng của đơn hàng
    public function getCustomerInfo($customerId) {
        $query = "SELECT * FROM customers WHERE CustomerId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Lấy danh sách sản phẩm trong đơn hàng
    public function getProductsInOrder($orderId) {
        $query = "SELECT a.Quantity, a.Price, p.Image, p.Name 
                  FROM orderdetails a, products p
                  WHERE a.ProductId = p.ProductId AND a.Order_Detail_Id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
