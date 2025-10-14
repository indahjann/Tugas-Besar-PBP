/**
 * Checkout functionality for UMKM Bookstore
 * Handles checkout form submission and order processing
 */

class CheckoutManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.form = document.getElementById('checkout-form');
        this.submitBtn = document.getElementById('submit-order-btn');
        this.shippingCost = 0;
        
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.bindEvents();
        this.calculateTotal();
        this.handleImageErrors();
    }

    handleImageErrors() {
        // Add error handling for all images in checkout
        const images = document.querySelectorAll('.item-image');
        console.log('Found images:', images.length);
        
        images.forEach((img, index) => {
            console.log(`Image ${index + 1} source:`, img.src);
            
            img.addEventListener('error', function() {
                console.error('Image failed to load:', this.src);
                this.src = 'https://via.placeholder.com/60x80?text=No+Image';
            });
        });
    }

    bindEvents() {
        // Form submission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });

        // Shipping method change
        const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
        shippingRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.updateShippingCost(e.target);
            });
        });

        // Radio option styling
        document.querySelectorAll('.radio-option').forEach(option => {
            option.addEventListener('click', () => {
                const radio = option.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    this.updateRadioStyles();
                    this.updateShippingCost(radio);
                }
            });
        });
    }

    updateRadioStyles() {
        document.querySelectorAll('.radio-option').forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    }

    updateShippingCost(radio) {
        const shippingMethod = radio.value;
        
        // Set shipping cost based on method
        if (shippingMethod === 'express') {
            this.shippingCost = 25000;
        } else {
            this.shippingCost = 0;
        }

        this.calculateTotal();
    }

    calculateTotal() {
        const subtotalElement = document.getElementById('subtotal-amount');
        const shippingElement = document.getElementById('shipping-amount');
        const totalElement = document.getElementById('total-amount');

        if (!subtotalElement || !shippingElement || !totalElement) return;

        // Get subtotal from data attribute or text content
        const subtotalText = subtotalElement.textContent.replace(/[^0-9]/g, '');
        const subtotal = parseInt(subtotalText) || 0;

        // Update shipping cost display
        shippingElement.textContent = this.formatPrice(this.shippingCost);

        // Calculate and update total
        const total = subtotal + this.shippingCost;
        totalElement.textContent = this.formatPrice(total);
    }

    async handleSubmit() {
        // Disable submit button
        this.submitBtn.disabled = true;
        const originalText = this.submitBtn.innerHTML;
        this.submitBtn.innerHTML = '<span class="loading-spinner"></span> Memproses...';

        // Clear previous errors
        this.clearErrors();

        // Get form data
        const formData = new FormData(this.form);
        const data = {
            address_text: formData.get('address_text'),
            phone_number: formData.get('phone_number'),
            shipping_method: formData.get('shipping_method'),
            notes: formData.get('notes')
        };

        try {
            const response = await fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Show success message
                this.showNotification('Pesanan berhasil dibuat! Mengalihkan ke halaman pesanan...', 'success');
                
                // Redirect to orders page after short delay
                setTimeout(() => {
                    window.location.href = '/orders?success=1';
                }, 1500);
            } else {
                // Handle validation errors
                if (result.errors) {
                    this.displayErrors(result.errors);
                } else {
                    this.showNotification(result.message || 'Terjadi kesalahan saat memproses pesanan', 'error');
                }
                
                // Re-enable button
                this.submitBtn.disabled = false;
                this.submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Checkout error:', error);
            this.showNotification('Terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
            
            // Re-enable button
            this.submitBtn.disabled = false;
            this.submitBtn.innerHTML = originalText;
        }
    }

    displayErrors(errors) {
        // Create error container if it doesn't exist
        let errorContainer = document.getElementById('error-container');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'error-container';
            errorContainer.className = 'error-message';
            this.form.insertBefore(errorContainer, this.form.firstChild);
        }

        // Build error list
        let errorHTML = '<strong>Terjadi kesalahan:</strong><ul class="error-list">';
        for (const [field, messages] of Object.entries(errors)) {
            messages.forEach(message => {
                errorHTML += `<li>${message}</li>`;
            });
        }
        errorHTML += '</ul>';

        errorContainer.innerHTML = errorHTML;
        
        // Scroll to error
        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    clearErrors() {
        const errorContainer = document.getElementById('error-container');
        if (errorContainer) {
            errorContainer.remove();
        }
    }

    formatPrice(price) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(price);
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `checkout-notification checkout-notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
            max-width: 400px;
        `;
        
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.25rem;">
                    ${type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ'}
                </span>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideInRight 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize checkout manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new CheckoutManager();
});

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);
