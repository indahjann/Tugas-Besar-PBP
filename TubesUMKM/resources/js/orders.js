/**
 * Orders functionality for UMKM Bookstore
 * Handles order listing, detail view, and order actions
 */

class OrderManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.checkSuccessParam();
    }

    bindEvents() {
        // Cancel order confirmation
        const cancelForms = document.querySelectorAll('form[action*="orders"][action*="cancel"]');
        cancelForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const confirmed = confirm('Yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.');
                if (!confirmed) {
                    e.preventDefault();
                }
            });
        });
    }

    checkSuccessParam() {
        // Auto-hide success message after 5 seconds
        const successAlert = document.querySelector('.bg-green-50');
        if (successAlert && window.location.search.includes('success=1')) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease-out';
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.remove();
                    // Remove success parameter from URL
                    const url = new URL(window.location);
                    url.searchParams.delete('success');
                    window.history.replaceState({}, '', url);
                }, 500);
            }, 5000);
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        } text-white max-w-md`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transition = 'opacity 0.3s ease-out';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Initialize order manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[class*="order"]')) {
        new OrderManager();
    }
});

export default OrderManager;
