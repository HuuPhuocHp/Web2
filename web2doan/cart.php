<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng | Adidas</title>
    <link rel="stylesheet" href="cart.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let cart = localStorage.getItem("cart") ? JSON.parse(localStorage.getItem("cart")) : [];
            let cartContainer = document.getElementById("cart-items");
            let totalPrice = 0;

            if (cart.length > 0) {
                cart.forEach((item, index) => {
                    let row = document.createElement("div");
                    row.classList.add("cart-item");

                    row.innerHTML = `
                        <div class="cart-item-img">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="cart-item-info">
                            <h3>${item.name}</h3>
                            <p>Thương hiệu: ${item.brand}</p>
                            <p class="cart-price">$${item.price}</p>
                            <p>Số lượng: ${item.quantity}</p>
                        </div>
                        <div class="cart-item-action">
                            <button class="remove-btn" onclick="removeItem(${index})">Xóa</button>
                        </div>
                    `;

                    cartContainer.appendChild(row);
                    totalPrice += item.price * item.quantity;
                });
            } else {
                document.getElementById("cart-message").innerText = "Giỏ hàng của bạn đang trống.";
                document.getElementById("checkout-btn").style.display = "none";
            }

            document.getElementById("total-price").innerText = `$${totalPrice}`;
        });

        // Xóa sản phẩm khỏi giỏ hàng
        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem("cart"));
            cart.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            window.location.reload();
        }

        // Hiển thị phương thức thanh toán
        function showPaymentOptions() {
            document.getElementById("payment-options").style.display = "block";
        }

        // Xử lý thanh toán
        function processPayment(method) {
            alert("Thanh toán thành công bằng " + method);
            localStorage.removeItem("cart");
            window.location.href = "cart.php"; // Làm mới trang
        }
    </script>
</head>
<body>
    <h1>Giỏ hàng</h1>
    <div id="cart-message"></div>
    <div id="cart-items"></div>
    
    <div class="cart-total">
        <h2>Tổng tiền: <span id="total-price">$0</span></h2>
        <button id="checkout-btn" onclick="showPaymentOptions()">Thanh toán</button>
    </div>

    <div id="payment-options" style="display: none;">
        <h3>Chọn phương thức thanh toán:</h3>
        <button onclick="processPayment('Tiền mặt')">Tiền mặt</button>
        <button onclick="processPayment('Chuyển khoản')">Chuyển khoản</button>
       
    </div>
    <div> <a href="Home.html">Tiep tuc mua sam</a></div>
</body>
</html>
