/**
 * Book Detail Page Functionality
 * Handles wishlist, cart, and read more functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    initBookDetail();
});

function initBookDetail() {
    initReadMoreFunctionality();
    initWishlistFunctionality();
    initAddToCartFunctionality();
    initShareFunctionality();
}

function initReadMoreFunctionality() {
    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    
    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const description = this.closest('.book-description');
            const preview = description.querySelector('.description-preview');
            const fullText = description.dataset.fullText;
            const isExpanded = this.textContent.includes('Lebih Sedikit');
            
            if (isExpanded) {
                preview.textContent = fullText.substring(0, 300) + '...';
                this.innerHTML = 'Baca Selengkapnya <i class="fas fa-chevron-down"></i>';
            } else {
                preview.textContent = fullText;
                this.innerHTML = 'Baca Lebih Sedikit <i class="fas fa-chevron-up"></i>';
            }
        });
    });
}

/**
 * Initialize Wishlist functionality
 */
function initWishlistFunctionality() {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.getAttribute('onclick')) {
                return;
            }
            
            const bookId = this.dataset.bookId;
            const icon = this.querySelector('i');
            const isActive = this.classList.contains('wishlist-active');
            
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>';
            this.disabled = true;
            
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ book_id: bookId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const isWishlisted = data.action === 'added';
                    
                    if (window.GlobalSync) {
                        window.GlobalSync.syncWishlistStatus(bookId, isWishlisted);
                    }
                    
                    if (isWishlisted) {
                        this.classList.add('wishlist-active');
                        this.innerHTML = '<i class="fas fa-heart"></i> <span>Favorit</span>';
                    } else {
                        this.classList.remove('wishlist-active');
                        this.innerHTML = '<i class="far fa-heart"></i> <span>Favorit</span>';
                    }
                    
                    const message = isWishlisted ? 'Buku ditambahkan ke wishlist!' : 'Buku dihapus dari wishlist!';
                    if (window.GlobalSync) {
                        window.GlobalSync.showNotification(message, isWishlisted ? 'success' : 'info');
                    } else {
                        showNotification(message, isWishlisted ? 'success' : 'info');
                    }
                } else {
                    throw new Error(data.message || 'Gagal mengubah status wishlist');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
                this.innerHTML = originalContent;
                if (window.GlobalSync) {
                    window.GlobalSync.showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                } else {
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
}

function initAddToCartFunctionality() {
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn:not(.add-to-cart-disabled)');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (this.getAttribute('onclick')) {
                return;
            }
            
            const bookId = this.dataset.bookId;
            const originalContent = this.innerHTML;
            
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menambahkan...</span>';
            this.disabled = true;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    product_id: bookId,
                    quantity: 1 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '<i class="fas fa-check"></i> <span>Ditambahkan!</span>';
                    this.style.background = 'linear-gradient(135deg, #28a745 0%, #20692a 100%)';
                    
                    // PERBAIKAN: Gunakan fungsi yang sama dengan cart.js
                    if (data.cart_count !== undefined) {
                        // Prioritas 1: Gunakan fungsi global dari CartManager
                        if (window.cartManager && typeof window.cartManager.updateCartBadge === 'function') {
                            window.cartManager.updateCartBadge(data.cart_count);
                        }
                        // Prioritas 2: Gunakan fungsi global updateCartBadgeWithCount
                        else if (window.updateCartBadgeWithCount) {
                            window.updateCartBadgeWithCount(data.cart_count);
                        }
                        // Prioritas 3: Gunakan GlobalSync
                        else if (window.GlobalSync && typeof window.GlobalSync.updateCartCounter === 'function') {
                            window.GlobalSync.updateCartCounter(data.cart_count);
                        }
                        // Fallback: Update manual
                        else {
                            updateCartCounterFallback(data.cart_count);
                        }
                    }
                    
                    if (window.GlobalSync) {
                        window.GlobalSync.showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    } else {
                        showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    }
                    
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                        this.style.background = '';
                        this.disabled = false;
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                this.innerHTML = originalContent;
                this.disabled = false;
                if (window.GlobalSync) {
                    window.GlobalSync.showNotification(error.message || 'Gagal menambahkan ke keranjang', 'error');
                } else {
                    showNotification(error.message || 'Gagal menambahkan ke keranjang', 'error');
                }
            });
        });
    });
}

function initShareFunctionality() {
    const shareBtns = document.querySelectorAll('.share-btn');
    
    shareBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const title = document.querySelector('.book-title').textContent;
            const url = window.location.href;
            
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: `Lihat buku menarik ini: ${title}`,
                    url: url
                }).catch(err => {
                    console.log('Share cancelled');
                });
            } else {
                copyToClipboard(url);
                showNotification('Link berhasil disalin ke clipboard!', 'success');
            }
        });
    });
}

/**
 * Update cart counter - Fallback implementation
 */
function updateCartCounterFallback(count) {
    // Update semua badge cart di navbar
    const cartBadges = document.querySelectorAll('.cart-badge, .cart-count, .cart-counter');
    
    cartBadges.forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
        
        // Add bounce animation
        badge.classList.add('cart-badge-bounce');
        setTimeout(() => {
            badge.classList.remove('cart-badge-bounce');
        }, 500);
    });
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        minWidth: '300px',
        padding: '16px',
        borderRadius: '8px',
        color: 'white',
        zIndex: '9999',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'space-between',
        boxShadow: '0 4px 20px rgba(0,0,0,0.15)',
        background: getNotificationColor(type),
        transform: 'translateX(400px)',
        transition: 'all 0.3s ease'
    });
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        removeNotification(notification);
    });
    
    setTimeout(() => {
        if (document.body.contains(notification)) {
            removeNotification(notification);
        }
    }, 5000);
}

function removeNotification(notification) {
    notification.style.transform = 'translateX(400px)';
    setTimeout(() => {
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
    }, 300);
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

function getNotificationColor(type) {
    switch(type) {
        case 'success': return 'linear-gradient(135deg, #28a745 0%, #20692a 100%)';
        case 'error': return 'linear-gradient(135deg, #dc3545 0%, #a71e2a 100%)';
        case 'warning': return 'linear-gradient(135deg, #ffc107 0%, #d39e00 100%)';
        default: return 'linear-gradient(135deg, #007bff 0%, #0056b3 100%)';
    }
}

// Export functions for potential use by other modules
window.BookDetail = {
    updateCartCounter: updateCartCounterFallback,
    showNotification
};