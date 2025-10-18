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
                    
                    // Update button state
                    if (isWishlisted) {
                        this.classList.add('wishlist-active');
                        this.innerHTML = '<i class="fas fa-heart"></i> <span>Favorit</span>';
                    } else {
                        this.classList.remove('wishlist-active');
                        this.innerHTML = '<i class="far fa-heart"></i> <span>Favorit</span>';
                    }
                    
                    // Update sessionStorage (sync with other pages)
                    updateSessionStorage(bookId, isWishlisted);
                    
                    // Show unified notification
                    const message = isWishlisted ? 'Buku ditambahkan ke wishlist' : 'Buku dihapus dari wishlist';
                    showNotification(message, isWishlisted ? 'success' : 'info');
                } else {
                    throw new Error(data.message || 'Gagal mengubah status wishlist');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
                this.innerHTML = originalContent;
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
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
                    
                    // Update cart badge
                    if (data.cart_count !== undefined) {
                        if (window.cartManager && typeof window.cartManager.updateCartBadge === 'function') {
                            window.cartManager.updateCartBadge(data.cart_count);
                        } else if (window.updateCartBadgeWithCount) {
                            window.updateCartBadgeWithCount(data.cart_count);
                        }
                    }
                    
                    // Show unified notification
                    showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    
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
                showNotification(error.message || 'Gagal menambahkan ke keranjang', 'error');
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
 * Update sessionStorage with wishlist state
 * This syncs with categories.js and wishlist.js
 */
function updateSessionStorage(bookId, isActive) {
    const wishlistState = JSON.parse(sessionStorage.getItem('wishlistState') || '{}');
    
    if (isActive) {
        wishlistState[bookId] = true;
    } else {
        delete wishlistState[bookId];
    }
    
    sessionStorage.setItem('wishlistState', JSON.stringify(wishlistState));
    console.log('[BookDetail] Updated sessionStorage:', wishlistState);
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

/**
 * Unified notification function (same as categories.js)
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const iconMap = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'info': 'info-circle',
        'warning': 'exclamation-triangle'
    };
    
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${iconMap[type]}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Export functions for potential use by other modules
window.BookDetail = {
    showNotification
};