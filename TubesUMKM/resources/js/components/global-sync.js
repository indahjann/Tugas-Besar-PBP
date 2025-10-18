/**
 * Global Sync System for BUKUKU
 * Handles synchronization between different components (cart counter, wishlist, notifications)
 */

class GlobalSync {
    constructor() {
        this.init();
    }

    init() {
        this.cartCounters = [];
        this.updateCartCounterElements();
        
        // Listen for custom events
        this.setupEventListeners();
        
        // Initialize cart count from server
        this.initializeCartCount();
    }

    setupEventListeners() {
        // Listen for cart updates from any component
        document.addEventListener('cartUpdated', (e) => {
            const newCount = e.detail.count;
            this.updateCartCounter(newCount);
        });

        // Listen for content loaded events (AJAX navigation)
        window.addEventListener('contentLoaded', () => {
            this.updateCartCounterElements();
        });
    }

    updateCartCounterElements() {
        // Find all cart counter elements
        this.cartCounters = [
            ...document.querySelectorAll('.cart-badge'),
            ...document.querySelectorAll('.cart-count'),
            ...document.querySelectorAll('.cart-counter')
        ];
    }

    async initializeCartCount() {
        // Check if user is authenticated
        const userDropdown = document.querySelector('[data-user-authenticated="true"]');
        if (!userDropdown) {
            this.updateCartCounter(0);
            return;
        }

        try {
            const response = await fetch('/cart/data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            if (response.ok) {
                const data = await response.json();
                const count = data.data?.count || 0;
                this.updateCartCounter(count);
            } else {
                this.updateCartCounter(0);
            }
        } catch (error) {
            console.error('Failed to initialize cart count:', error);
            this.updateCartCounter(0);
        }
    }

    updateCartCounter(count) {
        const numericCount = parseInt(count) || 0;
        
        // Update all cart counter elements
        this.updateCartCounterElements(); // Refresh elements in case of AJAX navigation
        
        this.cartCounters.forEach(counter => {
            if (counter) {
                counter.textContent = numericCount;
                
                // Show/hide badge based on count
                if (numericCount > 0) {
                    counter.style.display = 'flex';
                    
                    // Add animation effect
                    counter.style.transform = 'scale(1.3)';
                    counter.style.transition = 'transform 0.2s ease';
                    
                    setTimeout(() => {
                        counter.style.transform = 'scale(1)';
                    }, 200);
                } else {
                    counter.style.display = 'none';
                }
            }
        });

        // Update navbar instance if available
        if (window.bukukuNavbar && typeof window.bukukuNavbar.updateCartCount === 'function') {
            window.bukukuNavbar.updateCartCount(numericCount);
        }

        // Dispatch custom event for other components
        document.dispatchEvent(new CustomEvent('cartCountUpdated', {
            detail: { count: numericCount }
        }));
    }

    syncWishlistStatus(bookId, isWishlisted) {
        // Find all wishlist buttons for this book
        const wishlistBtns = document.querySelectorAll(`[data-book-id="${bookId}"].wishlist-btn`);
        
        wishlistBtns.forEach(btn => {
            if (isWishlisted) {
                btn.classList.add('wishlist-active');
                btn.innerHTML = '<i class="fas fa-heart"></i> <span>Favorit</span>';
            } else {
                btn.classList.remove('wishlist-active');
                btn.innerHTML = '<i class="far fa-heart"></i> <span>Favorit</span>';
            }
        });
    }

    showNotification(message, type = 'info', duration = 5000) {
        // Remove any existing notifications of the same type
        const existingNotifications = document.querySelectorAll(`.notification-${type}`);
        existingNotifications.forEach(notification => {
            this.removeNotification(notification);
        });

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Apply styles
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            minWidth: '300px',
            maxWidth: '400px',
            padding: '16px',
            borderRadius: '8px',
            color: 'white',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            boxShadow: '0 4px 20px rgba(0,0,0,0.15)',
            background: this.getNotificationColor(type),
            transform: 'translateX(450px)',
            transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
            fontSize: '14px',
            fontWeight: '500'
        });

        // Add to page
        document.body.appendChild(notification);

        // Animate in
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
        });

        // Handle close button
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            this.removeNotification(notification);
        });

        // Auto remove
        setTimeout(() => {
            if (document.body.contains(notification)) {
                this.removeNotification(notification);
            }
        }, duration);

        return notification;
    }

    removeNotification(notification) {
        notification.style.transform = 'translateX(450px)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }

    getNotificationIcon(type) {
        switch(type) {
            case 'success': return 'check-circle';
            case 'error': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            default: return 'info-circle';
        }
    }

    getNotificationColor(type) {
        switch(type) {
            case 'success': return 'linear-gradient(135deg, #10b981 0%, #065f46 100%)';
            case 'error': return 'linear-gradient(135deg, #ef4444 0%, #7f1d1d 100%)';
            case 'warning': return 'linear-gradient(135deg, #f59e0b 0%, #78350f 100%)';
            default: return 'linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%)';
        }
    }

    // Method to trigger cart count refresh from server
    async refreshCartCount() {
        await this.initializeCartCount();
    }
}

// Initialize GlobalSync when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (!window.GlobalSync) {
        window.GlobalSync = new GlobalSync();
    }
});

// Re-initialize after AJAX navigation
window.addEventListener('contentLoaded', () => {
    if (window.GlobalSync) {
        window.GlobalSync.updateCartCounterElements();
    }
});

export default GlobalSync;