<?php
class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function checkProductNameExists($name) {
        $query = "SELECT * FROM Products WHERE Name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    public function searchProducts($keyword, $start = 0, $limit = 10) {
        $query = "SELECT p.*, c.CategoryName, b.BrandName 
                  FROM Products p
                  LEFT JOIN Category c ON p.CategoriId = c.CategoryId
                  LEFT JOIN Brands b ON p.BrandId = b.BrandId
                  WHERE p.Name LIKE ? 
                  LIMIT ?, ?";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die('MySQL prepare error: ' . htmlspecialchars($this->conn->error));
        }
    
        $searchTerm = "%" . $keyword . "%"; 
        $stmt->bind_param("sii", $searchTerm, $start, $limit); // Sử dụng "sii" cho 2 int
        $stmt->execute();
        return $stmt->get_result();
    }
      
    // s cho chuỗi (string)
    // i cho số nguyên (integer)
    // d cho số thực (double)
    public function addProduct($data) {
        $query = "INSERT INTO Products (Name, Image, Quantity, Avaiable_quantity, Description, BuyPrice, SellPrice, Status, CategoriId, BrandId) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiissddii", $data['name'], $data['image'], $data['quantity'], $data['avaiable_quantity'], $data['description'], $data['buy_price'], $data['sell_price'], $data['status'], $data['id_categories'], $data['id_brands']);
        return $stmt->execute();
    }

    public function updateProduct($id, $data) {
        $query = "UPDATE Products SET Name=?, Image=?, Quantity=?, Avaiable_quantity=?, Description=?, BuyPrice=?, SellPrice=?, Status=?, CategoriId=?, BrandId=? WHERE ProductId=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssiisddiiii", $data['name'], $data['image'], $data['quantity'], $data['avaiable_quantity'], $data['description'], $data['buy_price'], $data['sell_price'], $data['status'], $data['id_categories'], $data['id_brands'], $id);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM Products WHERE ProductId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
