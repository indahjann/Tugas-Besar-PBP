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

/**
 * Initialize Read More/Less functionality for book description
 */
function initReadMoreFunctionality() {
    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    
    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const description = this.closest('.book-description');
            const preview = description.querySelector('.description-preview');
            const fullText = description.dataset.fullText;
            const isExpanded = this.textContent.includes('Lebih Sedikit');
            
            if (isExpanded) {
                // Collapse
                preview.textContent = fullText.substring(0, 300) + '...';
                this.innerHTML = 'Baca Selengkapnya <i class="fas fa-chevron-down"></i>';
            } else {
                // Expand
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
            
            // Check if user is authenticated (handled by Blade template)
            if (this.getAttribute('onclick')) {
                return; // Let the onclick handle redirect to login
            }
            
            const bookId = this.dataset.bookId;
            const icon = this.querySelector('i');
            const isActive = this.classList.contains('wishlist-active');
            
            // Show loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Loading...</span>';
            this.disabled = true;
            
            // Make API request
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
                    
                    // Sync across all components using GlobalSync
                    if (window.GlobalSync) {
                        window.GlobalSync.syncWishlistStatus(bookId, isWishlisted);
                    }
                    
                    // Update current button
                    if (isWishlisted) {
                        this.classList.add('wishlist-active');
                        this.innerHTML = '<i class="fas fa-heart"></i> <span>Favorit</span>';
                    } else {
                        this.classList.remove('wishlist-active');
                        this.innerHTML = '<i class="far fa-heart"></i> <span>Favorit</span>';
                    }
                    
                    // Show unified notification
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

/**
 * Initialize Add to Cart functionality
 */
function initAddToCartFunctionality() {
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn:not(.add-to-cart-disabled)');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Check if user is authenticated (handled by Blade template)
            if (this.getAttribute('onclick')) {
                return; // Let the onclick handle redirect to login
            }
            
            const bookId = this.dataset.bookId;
            const originalContent = this.innerHTML;
            
            // Show loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Menambahkan...</span>';
            this.disabled = true;
            
            // Make API request
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
                    // Show success state
                    this.innerHTML = '<i class="fas fa-check"></i> <span>Ditambahkan!</span>';
                    this.style.background = 'linear-gradient(135deg, #28a745 0%, #20692a 100%)';
                    
                    // Update cart counter globally
                    if (data.cart_count !== undefined) {
                        if (window.GlobalSync) {
                            window.GlobalSync.updateCartCounter(data.cart_count);
                        } else {
                            updateCartCounter(data.cart_count);
                        }
                    }
                    
                    // Show unified notification
                    if (window.GlobalSync) {
                        window.GlobalSync.showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    } else {
                        showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                    }
                    
                    // Reset button after 2 seconds
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

/**
 * Initialize Share functionality
 */
function initShareFunctionality() {
    const shareBtns = document.querySelectorAll('.share-btn');
    
    shareBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const title = document.querySelector('.book-title').textContent;
            const url = window.location.href;
            
            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: `Lihat buku menarik ini: ${title}`,
                    url: url
                }).catch(err => {
                    console.log('Share cancelled');
                });
            } else {
                // Fallback: Copy to clipboard
                copyToClipboard(url);
                showNotification('Link berhasil disalin ke clipboard!', 'success');
            }
        });
    });
}

/**
 * Update cart counter in navigation
 */
function updateCartCounter(count) {
    const cartCounters = document.querySelectorAll('.cart-count, .cart-counter');
    cartCounters.forEach(counter => {
        counter.textContent = count;
        
        // Add animation
        counter.style.transform = 'scale(1.2)';
        setTimeout(() => {
            counter.style.transform = 'scale(1)';
        }, 200);
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        // Fallback for older browsers
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
 * Show notification to user
 */
function showNotification(message, type = 'info') {
    // Create notification element
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
    
    // Add styles
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
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Handle close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        removeNotification(notification);
    });
    
    // Auto remove after 5 seconds
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
    updateCartCounter,
    showNotification
};