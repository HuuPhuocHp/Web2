document.addEventListener("DOMContentLoaded", function () {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartContainer = document.getElementById("cart-items");
    
    if (cart.length > 0) {
        cart.forEach((item, index) => {
            let row = document.createElement("div");
            row.innerHTML = `
                <p>${item.name} - $${item.price} x ${item.quantity}</p>
                <button onclick="removeItem(${index})">Xóa</button>
            `;
            cartContainer.appendChild(row);
        });
    } else {
        cartContainer.innerHTML = "<p>Giỏ hàng trống.</p>";
    }

    document.getElementById("address").addEventListener("change", function () {
        document.getElementById("new-address").style.display = this.value === "new" ? "block" : "none";
    });
});

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem("cart"));
    cart.splice(index, 1);
    localStorage.setItem("cart", JSON.stringify(cart));
    window.location.reload();
}

function checkout(method) {
    let address = document.getElementById("address").value === "new" ? document.getElementById("new-address").value : document.getElementById("address").value;
    let cart = JSON.parse(localStorage.getItem("cart"));

    fetch("checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ cart, address, method })
    })
    .then(response => response.text())
    .then(data => {
        alert("Thanh toán thành công!");
        localStorage.removeItem("cart");
        window.location.href = "order_history.php";
    });
}
