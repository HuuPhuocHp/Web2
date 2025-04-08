<?php
$products = [
    ["id" => 1, "brand" => "Nike", "price" => 180, "image" => "anhsanpham/anh1.png", "name" => "Nike 1"],
    ["id" => 2, "brand" => "Nike", "price" => 250, "image" => "anhsanpham/anh2.png", "name" => "Nike 2"],
    ["id" => 3, "brand" => "Nike", "price" => 140, "image" => "anhsanpham/anh3.png", "name" => "Nike 3"],
    ["id" => 4, "brand" => "Converse", "price" => 200, "image" => "anhsanpham/anh4.png", "name" => "Converse 4"],
    ["id" => 5, "brand" => "Converse", "price" => 220, "image" => "anhsanpham/anh5.png", "name" => "Converse 5"],
    ["id" => 6, "brand" => "Converse", "price" => 160, "image" => "anhsanpham/anh6.png", "name" => "Converse 6"],
    ["id" => 7, "brand" => "Adidas", "price" => 190, "image" => "anhsanpham/anh7.png", "name" => "Adidas 7"],
    ["id" => 8, "brand" => "Adidas", "price" => 230, "image" => "anhsanpham/anh8.png", "name" => "Adidas 8"],
    ["id" => 9, "brand" => "Adidas", "price" => 170, "image" => "anhsanpham/anh9.png", "name" => "Adidas 9"]
];

foreach ($products as $product) {
    echo '<div class="product-item" data-id="'.$product["id"].'" data-brand="'.$product["brand"].'" data-price="'.$product["price"].'">
            <img src="'.$product["image"].'" alt="'.$product["name"].'">
            <h3>'.$product["name"].'</h3>
            <p>$'.$product["price"].'</p>
            <a href="#" class="add-to-cart">Thêm vào giỏ</a>
            <a href="#" class="btn-secondary">Thông tin</a>
          </div>';
}
?>
