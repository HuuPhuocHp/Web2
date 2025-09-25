// js/cart.js

/**
 * Hiển thị một thông báo nhỏ ở góc màn hình.
 * @param {string} message Nội dung thông báo.
 * @param {string} type Loại thông báo ('success' hoặc 'error').
 */
function notify(message, type = "success") {
    const div = document.createElement("div");
    div.className = `toast ${type}`;
    div.innerText = message;
    Object.assign(div.style, {
        position: "fixed",
        bottom: "20px",
        right: "20px",
        background: type === "error" ? "#e74c3c" : "#2ecc71",
        color: "white",
        padding: "12px 20px",
        borderRadius: "5px",
        zIndex: 9999,
        fontSize: "15px",
        boxShadow: "0 4px 8px rgba(0,0,0,0.2)",
        opacity: 0,
        transition: "opacity 0.3s, transform 0.3s",
        transform: "translateY(20px)"
    });
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = 1; div.style.transform = "translateY(0)"; }, 10);
    setTimeout(() => { div.style.opacity = 0; div.style.transform = "translateY(20px)"; setTimeout(() => div.remove(), 300); }, 3000);
}

// Helpers
const getCurrentUser = () => JSON.parse(localStorage.getItem('user') || 'null');
const cartStorageKey = (u = getCurrentUser()) => (u ? `cart_${u.id}` : 'cart_guest');
const ordersStorageKey = (u = getCurrentUser()) => (u ? `orders_${u.id}` : null);

// Di trú từ khoá cũ 'cart' -> đúng key (guest hoặc user)
(function migrateLegacyCartKey(){
    try {
        const legacy = localStorage.getItem('cart');
        if (!legacy) return;
        const arr = JSON.parse(legacy || '[]');
        if (!Array.isArray(arr) || !arr.length) { localStorage.removeItem('cart'); return; }
        const key = cartStorageKey();
        const curr = JSON.parse(localStorage.getItem(key) || '[]');
        // Hợp nhất theo productId/id
        const map = new Map();
        [...curr, ...arr].forEach(it => {
            const id = Number(it.productId ?? it.ProductId ?? it.id);
            const qty = Number(it.qty ?? it.quantity ?? 1);
            if (!map.has(id)) map.set(id, { productId: id, qty: 0 });
            map.get(id).qty += qty;
        });
        localStorage.setItem(key, JSON.stringify([...map.values()]));
        localStorage.removeItem('cart');
    } catch { /* noop */ }
})();

// Cart API (duy nhất) — luôn lưu theo từng user (hoặc guest)
const Cart = {
    async getProductById(id) {
        try {
            const response = await fetch("database/product.json");
            if (!response.ok) throw new Error('Không thể tải file sản phẩm.');
            const products = await response.json();
            return products.find(p => Number(p.ProductId) === Number(id)) || null;
        } catch (error) {
            console.error("Lỗi getProductById:", error);
            return null;
        }
    },

    // Lấy/Lưu giỏ cho user hiện tại
    getCart() {
        try { return JSON.parse(localStorage.getItem(cartStorageKey()) || '[]'); } catch { return []; }
    },
    setCart(items) {
        localStorage.setItem(cartStorageKey(), JSON.stringify(items || []));
        window.dispatchEvent(new CustomEvent('cartUpdated'));
    },

    // Thêm sản phẩm
    async add(id, quantity = 1) {
        const user = getCurrentUser();
        if (!user) { notify('Vui lòng đăng nhập để thêm sản phẩm!', 'error'); return; }
        const product = await this.getProductById(id);
        if (!product) { notify('Lỗi: Sản phẩm không tồn tại.', 'error'); return; }
        const qty = Number(quantity) || 1;
        if (Number(product.Avaiable_quantity ?? product.Available_quantity ?? product.Quantity ?? 0) < qty) {
            notify(`'${product.Name}' không đủ số lượng tồn.`, 'error');
            return;
        }
        const cart = this.getCart();
        const pid = Number(product.ProductId);
        const i = cart.findIndex(x => Number(x.productId ?? x.id) === pid);
        if (i >= 0) cart[i].qty = Number(cart[i].qty ?? cart[i].quantity ?? 0) + qty;
        else cart.push({ productId: pid, qty });
        this.setCart(cart);
        notify(`Đã thêm '${product.Name}' vào giỏ hàng!`);
    },

    // Cập nhật số lượng
    async update(id, quantity) {
        let qty = Number(quantity);
        if (!qty || qty <= 0) { this.delete(id); return; }
        const product = await this.getProductById(id);
        if (!product) { notify('Lỗi: Sản phẩm không tồn tại.', 'error'); return; }
        const max = Number(product.Avaiable_quantity ?? product.Available_quantity ?? product.Quantity ?? 0);
        if (qty > max) { notify(`'${product.Name}' chỉ còn ${max} sản phẩm.`, 'error'); window.dispatchEvent(new CustomEvent('cartUpdated')); return; }
        const cart = this.getCart().map(x => {
            if (Number(x.productId ?? x.id) === Number(id)) return { ...x, qty };
            return x;
        });
        this.setCart(cart);
    },

    // Xoá 1 sản phẩm
    delete(id) {
        const cart = this.getCart().filter(x => Number(x.productId ?? x.id) !== Number(id));
        this.setCart(cart);
    },
    removeFromCart(id) { this.delete(id); },

    // Xoá sạch giỏ
    clear() { this.setCart([]); },

    // Đặt hàng theo user hiện tại
    placeOrder(orderInfo = {}) {
        const user = getCurrentUser();
        if (!user) throw new Error('Vui lòng đăng nhập để đặt hàng');
        const items = this.getCart();
        if (!items.length) throw new Error('Giỏ hàng trống');
        const key = ordersStorageKey(user);
        const orders = JSON.parse(localStorage.getItem(key) || '[]');
        const order = { id: Date.now(), date: new Date().toISOString(), items, ...orderInfo };
        orders.push(order);
        localStorage.setItem(key, JSON.stringify(orders));
        this.clear();
        return order;
    },

    getOrders() {
        const user = getCurrentUser();
        if (!user) return [];
        try { return JSON.parse(localStorage.getItem(ordersStorageKey(user)) || '[]'); } catch { return []; }
    }
};

// Hàm tiện ích cho các nút "Thêm giỏ hàng" trong HTML
async function handleAddToCart(productId) {
    const user = getCurrentUser();
    if (!user) { notify('Vui lòng đăng nhập để thêm sản phẩm!', 'error'); setTimeout(() => { window.location.href = 'login.html'; }, 1200); return; }
    await Cart.add(productId, 1);
}

const CART_PAGE = 'view_cart.html';
function goToCart() { window.location.href = CART_PAGE; }

// Xoá cờ redirect cũ nếu có
document.addEventListener('DOMContentLoaded', () => {
    localStorage.removeItem('redirectToCart');
    sessionStorage.removeItem('redirectToCart');
});

// Gắn Cart vào window để các trang khác có thể truy cập window.Cart
try { window.Cart = Object.assign({}, Cart); } catch {}


