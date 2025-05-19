// Update cart count in header
function updateCartCount() {
   fetch('components/get_cart_count.php')
      .then(response => response.json())
      .then(data => {
         const cartCount = document.querySelector('.cart-count');
         if (cartCount) {
            cartCount.textContent = `(${data.count})`;
         }
      });
}

// Animate cart icon when item is added
function animateCartIcon() {
   const cartIcon = document.querySelector('.cart-icon');
   if (cartIcon) {
      cartIcon.classList.add('animate');
      setTimeout(() => {
         cartIcon.classList.remove('animate');
      }, 1000);
   }
}

// Add item to cart
function addToCart(productId, quantity = 1) {
   if (!productId) return;

   fetch('components/add_cart.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `pid=${productId}&qty=${quantity}`
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         updateCartCount();
         animateCartIcon();
         showMessage(data.message, 'success');
      } else {
         showMessage(data.message, 'error');
      }
   });
}

// Show message to user
function showMessage(message, type = 'info') {
   const messageDiv = document.createElement('div');
   messageDiv.className = `message ${type}`;
   messageDiv.innerHTML = `
      <span>${message}</span>
      <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
   `;
   document.body.appendChild(messageDiv);
   setTimeout(() => messageDiv.remove(), 3000);
}

// Initialize cart functionality
document.addEventListener('DOMContentLoaded', function() {
   updateCartCount();

   // Handle quantity updates
   const quantityInputs = document.querySelectorAll('.qty-input');
   quantityInputs.forEach(input => {
      // Store initial value
      input.dataset.oldValue = input.value;

      // Handle input change
      input.addEventListener('change', function() {
         const cartItem = this.closest('.cart-item');
         const cartId = cartItem.dataset.cartId;
         const newQuantity = parseInt(this.value);
         
         if (newQuantity >= 1 && newQuantity <= 99) {
            updateCartQuantity(cartId, newQuantity);
            this.dataset.oldValue = newQuantity;
         } else {
            this.value = this.dataset.oldValue;
         }
      });
   });

   // Handle plus/minus buttons
   const plusButtons = document.querySelectorAll('.qty-btn.plus');
   const minusButtons = document.querySelectorAll('.qty-btn.minus');

   plusButtons.forEach(button => {
      button.addEventListener('click', function() {
         const cartItem = this.closest('.cart-item');
         const cartId = cartItem.dataset.cartId;
         const input = cartItem.querySelector('.qty-input');
         const newQuantity = parseInt(input.value) + 1;
         
         if (newQuantity <= 99) {
            input.value = newQuantity;
            updateCartQuantity(cartId, newQuantity);
         }
      });
   });

   minusButtons.forEach(button => {
      button.addEventListener('click', function() {
         const cartItem = this.closest('.cart-item');
         const cartId = cartItem.dataset.cartId;
         const input = cartItem.querySelector('.qty-input');
         const newQuantity = parseInt(input.value) - 1;
         
         if (newQuantity >= 1) {
            input.value = newQuantity;
            updateCartQuantity(cartId, newQuantity);
         }
      });
   });

   // Handle remove buttons
   const removeButtons = document.querySelectorAll('.remove-btn');
   removeButtons.forEach(button => {
      button.addEventListener('click', function() {
         const cartItem = this.closest('.cart-item');
         const cartId = cartItem.dataset.cartId;
         removeCartItem(cartId);
      });
   });
});

function updateCartQuantity(cartId, quantity) {
   fetch('components/update_cart.php', {
      method: 'POST',
      headers: {
         'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `cart_id=${cartId}&quantity=${quantity}`
   })
   .then(response => response.json())
   .then(data => {
      if (data.success) {
         // Update the subtotal for this item
         const cartItem = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
         if (cartItem) {
            const subtotalElement = cartItem.querySelector('.subtotal span');
            if (subtotalElement) {
               subtotalElement.textContent = data.subtotal + ' VNĐ';
            }
         }
         
         // Update the grand total
         const grandTotalElement = document.querySelector('#grand-total');
         if (grandTotalElement) {
            grandTotalElement.textContent = data.grand_total + ' VNĐ';
         }

         // Update the subtotal
         const subtotalElement = document.querySelector('#subtotal');
         if (subtotalElement) {
            subtotalElement.textContent = (data.grand_total - 30000) + ' VNĐ';
         }

         // Show success message
         showMessage('Đã cập nhật số lượng!', 'success');
      } else {
         showMessage('Không thể cập nhật số lượng. Vui lòng thử lại.', 'error');
      }
   })
   .catch(error => {
      console.error('Error:', error);
      showMessage('Đã xảy ra lỗi khi cập nhật giỏ hàng.', 'error');
   });
}

function removeCartItem(cartId) {
   if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
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
            // Remove the cart item element
            const cartItem = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
            if (cartItem) {
               cartItem.remove();
            }
            
            // Update the grand total
            const grandTotalElement = document.querySelector('#grand-total');
            if (grandTotalElement) {
               grandTotalElement.textContent = data.grand_total + ' VNĐ';
            }

            // Update the subtotal
            const subtotalElement = document.querySelector('#subtotal');
            if (subtotalElement) {
               subtotalElement.textContent = (data.grand_total - 30000) + ' VNĐ';
            }

            // Show success message
            showMessage('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
            
            // Check if cart is empty
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
               location.reload(); // Reload to show empty cart message
            }
         } else {
            showMessage('Không thể xóa sản phẩm. Vui lòng thử lại.', 'error');
         }
      })
      .catch(error => {
         console.error('Error:', error);
         showMessage('Đã xảy ra lỗi khi xóa sản phẩm.', 'error');
      });
   }
} 