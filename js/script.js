// Cart quantity update
document.querySelectorAll('.qty').forEach(input => {
    input.addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
        if (this.value > 99) {
            this.value = 99;
        }
    });
});

// Update cart count in header
function updateCartCount() {
    fetch('components/get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.fa-shopping-cart + span').textContent = `(${data.count})`;
        });
}

// Update cart item quantity
function updateCartItem(cartId, quantity) {
    fetch('components/update_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cart_id=${cartId}&qty=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                location.reload();
            }
        });
}

// Remove cart item
function removeCartItem(cartId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        fetch('components/remove_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    location.reload();
                }
            });
    }
}

// Calculate total
function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.sub-total span').forEach(element => {
        total += parseInt(element.textContent.replace(/[^0-9]/g, ''));
    });
    document.querySelector('.grand-total .price').textContent = total.toLocaleString() + ' VNĐ';
}

// Initialize cart functionality
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    updateCartCount();
});

document.addEventListener("DOMContentLoaded", () => {
    const userBtn = document.querySelector('#user-btn');
    const profile = document.querySelector('.profile');

    if (userBtn && profile) {
        userBtn.addEventListener('click', () => {
            profile.classList.toggle('active');
        });

        // Ẩn khi click ra ngoài
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !profile.contains(e.target)) {
                profile.classList.remove('active');
            }
        });
    }
});