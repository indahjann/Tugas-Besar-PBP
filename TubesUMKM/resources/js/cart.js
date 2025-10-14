/**
 * Cart functionality for UMKM Bookstore
 * Handles add to cart, update quantity, remove items, etc.
 */

class CartManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateCartCount();
        this.initializeMinusButtons();
    }

    bindEvents() {
        // Add to cart buttons (only handle specific cart page buttons to avoid conflicts)
        document.addEventListener('click', (e) => {
            // Only handle cart page specific buttons, let book-card.js handle product page buttons
            if (e.target.matches('.add-to-cart-btn:not([data-book-id])')) {
                e.preventDefault();
                this.handleAddToCart(e.target);
            }
        });

        // Quantity controls in cart page
        document.addEventListener('click', (e) => {
            if (e.target.matches('.qty-btn.plus')) {
                this.handleQuantityIncrease(e.target);
            } else if (e.target.matches('.qty-btn.minus')) {
                this.handleQuantityDecrease(e.target);
            } else if (e.target.matches('.delete-item-btn')) {
                this.handleRemoveItem(e.target);
            }
        });

        // Quantity input change
        document.addEventListener('change', (e) => {
            if (e.target.matches('.qty-input')) {
                this.handleQuantityChange(e.target);
            }
        });

        // Select all functionality
        document.addEventListener('change', (e) => {
            if (e.target.matches('#select-all')) {
                this.handleSelectAll(e.target);
            } else if (e.target.matches('#store-checkbox')) {
                this.handleStoreSelect(e.target);
            }
        });

        // Delete selected items
        document.addEventListener('click', (e) => {
            if (e.target.matches('#delete-selected')) {
                this.handleDeleteSelected();
            }
        });

        // Checkout button
        document.addEventListener('click', (e) => {
            if (e.target.matches('#checkout-btn')) {
                this.handleCheckout();
            }
        });
    }

    async handleAddToCart(button) {
        const bookId = button.getAttribute('data-book-id') || button.getAttribute('data-product-id');
        const quantity = 1; // Default quantity

        if (!bookId) {
            this.showNotification('Error: Product ID tidak ditemukan', 'error');
            return;
        }
        
        // Prevent rapid fire clicks
        if (button.disabled || button.dataset.cartProcessing === 'true') {
            return;
        }
        
        // Mark as processing
        button.dataset.cartProcessing = 'true';

        // Show loading state
        const originalText = button.textContent;
        button.textContent = 'Menambahkan...';
        button.disabled = true;

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: bookId,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                // Update cart count immediately with response data if available
                if (data.cart_count !== undefined) {
                    this.updateCartBadge(data.cart_count);
                } else {
                    // Fallback to fetching count
                    this.updateCartCount();
                }
                button.textContent = '✓ Ditambahkan';
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.dataset.cartProcessing = 'false';
                }, 2000);
            } else {
                throw new Error(data.message || 'Gagal menambahkan produk ke keranjang');
            }
        } catch (error) {
            this.showNotification(error.message || 'Gagal menambahkan produk ke keranjang', 'error');
            button.textContent = originalText;
            button.disabled = false;
            button.dataset.cartProcessing = 'false';
        }
    }

    async handleQuantityIncrease(button) {
        const itemId = button.getAttribute('data-item-id');
        const qtyInput = button.parentElement.querySelector('.qty-input');
        const newQuantity = parseInt(qtyInput.value) + 1;
        
        await this.updateItemQuantity(itemId, newQuantity, qtyInput);
    }

    async handleQuantityDecrease(button) {
        const itemId = button.getAttribute('data-item-id');
        const qtyInput = button.parentElement.querySelector('.qty-input');
        const currentQuantity = parseInt(qtyInput.value);
        
        if (currentQuantity > 1) {
            const newQuantity = currentQuantity - 1;
            await this.updateItemQuantity(itemId, newQuantity, qtyInput);
        }
    }

    async handleQuantityChange(input) {
        const itemId = input.getAttribute('data-item-id');
        const newQuantity = parseInt(input.value);
        
        if (newQuantity >= 1) {
            await this.updateItemQuantity(itemId, newQuantity, input);
        } else {
            input.value = 1;
        }
    }

    async updateItemQuantity(itemId, quantity, inputElement) {
        const originalValue = inputElement.value;
        
        try {
            const response = await fetch(`/cart/update/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                inputElement.value = quantity;
                this.updateMinusButtonState(itemId, quantity);
                this.updateCartSummary();
                this.updateCartCount(); 
                this.showNotification('Jumlah produk berhasil diperbarui', 'success');
            } else {
                throw new Error(data.message || 'Gagal memperbarui jumlah produk');
            }
        } catch (error) {
            inputElement.value = originalValue;
            this.updateMinusButtonState(itemId, originalValue);
            this.showNotification(error.message || 'Gagal memperbarui jumlah produk', 'error');
        }
    }
    
    updateMinusButtonState(itemId, quantity) {
        const minusButton = document.querySelector(`.qty-btn.minus[data-item-id="${itemId}"]`);
        if (minusButton) {
            if (quantity <= 1) {
                minusButton.disabled = true;
            } else {
                minusButton.disabled = false;
            }
        }
    }
    
    initializeMinusButtons() {
        // Set initial state for all minus buttons based on current quantities
        const qtyInputs = document.querySelectorAll('.qty-input');
        qtyInputs.forEach(input => {
            const itemId = input.getAttribute('data-item-id');
            const quantity = parseInt(input.value) || 1;
            this.updateMinusButtonState(itemId, quantity);
        });
    }

    async handleRemoveItem(button) {
        const itemId = button.getAttribute('data-item-id');
        const cartItem = button.closest('.cart-item');
        
        if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            return;
        }

        try {
            const response = await fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Remove item from DOM with animation
                cartItem.style.transition = 'opacity 0.3s ease-out';
                cartItem.style.opacity = '0';
                
                setTimeout(() => {
                    cartItem.remove();
                    this.updateCartSummary();
                    this.checkEmptyCart();
                }, 300);
                
                this.showNotification('Produk berhasil dihapus dari keranjang', 'success');
                this.updateCartCount();
            } else {
                throw new Error(data.message || 'Gagal menghapus produk dari keranjang');
            }
        } catch (error) {
            this.showNotification(error.message || 'Gagal menghapus produk dari keranjang', 'error');
        }
    }

    handleSelectAll(checkbox) {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox-input');
        const storeCheckbox = document.getElementById('store-checkbox');
        
        itemCheckboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        
        if (storeCheckbox) {
            storeCheckbox.checked = checkbox.checked;
        }
    }

    handleStoreSelect(checkbox) {
        const itemCheckboxes = document.querySelectorAll('.item-checkbox-input');
        const selectAllCheckbox = document.getElementById('select-all');
        
        itemCheckboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkbox.checked;
        }
    }

    async handleDeleteSelected() {
        const selectedItems = document.querySelectorAll('.item-checkbox-input:checked');
        
        if (selectedItems.length === 0) {
            this.showNotification('Pilih item yang ingin dihapus', 'warning');
            return;
        }

        if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedItems.length} item yang dipilih?`)) {
            return;
        }

        const itemIds = Array.from(selectedItems).map(cb => cb.getAttribute('data-item-id'));
        
        try {
            // Delete items one by one (could be optimized with bulk delete endpoint)
            const deletePromises = itemIds.map(itemId => 
                fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    }
                })
            );

            await Promise.all(deletePromises);

            // Remove items from DOM
            selectedItems.forEach(cb => {
                const cartItem = cb.closest('.cart-item');
                cartItem.style.transition = 'opacity 0.3s ease-out';
                cartItem.style.opacity = '0';
                
                setTimeout(() => {
                    cartItem.remove();
                }, 300);
            });

            setTimeout(() => {
                this.updateCartSummary();
                this.checkEmptyCart();
            }, 300);

            this.showNotification('Item yang dipilih berhasil dihapus', 'success');
            this.updateCartCount();
            
            // Uncheck select all
            const selectAllCheckbox = document.getElementById('select-all');
            const storeCheckbox = document.getElementById('store-checkbox');
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            if (storeCheckbox) storeCheckbox.checked = false;
            
        } catch (error) {
            this.showNotification('Gagal menghapus item yang dipilih', 'error');
        }
    }

    async updateCartCount() {
        try {
            const response = await fetch('/cart/data', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    this.updateCartBadge(data.data.count);
                }
            }
        } catch (error) {
            // Silent fail for cart count update
        }
    }

    updateCartBadge(count) {
        // Use the global unified badge update function if available
        if (window.updateCartBadgeWithCount) {
            window.updateCartBadgeWithCount(count);
        } else {
            // Fallback to local implementation
            const cartBadges = document.querySelectorAll('.cart-badge, .cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'inline' : 'none';
            });
        }
    }

    async updateCartSummary() {
        try {
            const response = await fetch('/cart/data', {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    const { subtotal, items } = data.data;
                    
                    // Update summary
                    const totalPriceEl = document.querySelector('.total-price');
                    const subtotalPriceEl = document.querySelector('.subtotal-price');
                    const itemCountEl = document.querySelector('.summary-row span:first-child');
                    
                    if (totalPriceEl) totalPriceEl.textContent = `Rp${this.formatPrice(subtotal)}`;
                    if (subtotalPriceEl) subtotalPriceEl.textContent = `Rp${this.formatPrice(subtotal)}`;
                    if (itemCountEl) itemCountEl.textContent = `Total Harga (${items.length} Barang)`;
                }
            }
        } catch (error) {
            // Silent fail for cart summary update
        }
    }

    checkEmptyCart() {
        const cartItems = document.querySelectorAll('.cart-item');
        if (cartItems.length === 0) {
            // Redirect to empty cart state or reload page
            location.reload();
        }
    }

    handleCheckout() {
        // Check if there are items in cart
        const cartItems = document.querySelectorAll('.cart-item');
        
        if (cartItems.length === 0) {
            this.showNotification('Keranjang Anda kosong. Tambahkan produk terlebih dahulu.', 'warning');
            return;
        }

        // Redirect to checkout page
        window.location.href = '/checkout';
    }

    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `cart-notification cart-notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">
                    ${type === 'success' ? '✓' : type === 'error' ? '✗' : type === 'warning' ? '⚠' : 'ℹ'}
                </span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideInRight 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
}

// Initialize cart manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new CartManager();
});

// Export for use in other modules
export default CartManager;