// Book Card Interactive Functions

// Toggle Wishlist - FIXED for carousel clones
window.toggleWishlist = function(bookId, clickedButton = null) {
    // Check if user is authenticated
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }
    
    // Find ALL buttons for this book ID (including clones)
    const allButtons = document.querySelectorAll(`[data-book-id="${bookId}"].btn-favorites`);
    if (allButtons.length === 0) return;
    
    // Use clicked button if provided, otherwise first button
    const referenceButton = clickedButton || allButtons[0];
    const isActive = referenceButton.classList.contains('active');
    
    // Pause carousel during button interaction to prevent conflicts
    pauseCarouselTemporary();
    
    // Update ALL instances (original + clones) optimistically
    allButtons.forEach(button => {
        const icon = button.querySelector('i');
        
        button.classList.toggle('active');
        icon.className = isActive ? 'far fa-heart' : 'fas fa-heart';
        button.title = isActive ? 'Add to favorites' : 'Remove from favorites';
    });
    
    // Send AJAX request
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            book_id: bookId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast(data.message || (isActive ? 'Removed from wishlist' : 'Added to wishlist'), 'success');
        } else {
            // Revert optimistic update on ALL instances
            allButtons.forEach(button => {
                const icon = button.querySelector('i');
                button.classList.toggle('active');
                icon.className = isActive ? 'fas fa-heart' : 'far fa-heart';
                button.title = isActive ? 'Remove from favorites' : 'Add to favorites';
            });
            showToast(data.message || 'Failed to update wishlist', 'error');
        }
    })
    .catch(error => {
        // Revert optimistic update on ALL instances
        allButtons.forEach(button => {
            const icon = button.querySelector('i');
            button.classList.toggle('active');
            icon.className = isActive ? 'fas fa-heart' : 'far fa-heart';
            button.title = isActive ? 'Remove from favorites' : 'Add to favorites';
        });
        showToast('Network error. Please try again.', 'error');
    });
};

// Add to Cart with duplicate prevention - FIXED for carousel clones
window.addToCart = function(bookId, clickedButton = null) {
    // Check if user is authenticated
    if (!isAuthenticated()) {
        showLoginPrompt();
        return;
    }
    
    // Find ALL buttons for this book ID (including clones)
    const allButtons = document.querySelectorAll(`[data-book-id="${bookId}"].btn-add-to-cart`);
    if (allButtons.length === 0) return;
    
    // Use clicked button if provided, otherwise first button
    const referenceButton = clickedButton || allButtons[0];
    
    // Pause carousel during button interaction to prevent conflicts
    pauseCarouselTemporary();
    
    // Prevent multiple rapid clicks - check any button is processing
    const isAnyProcessing = Array.from(allButtons).some(btn => 
        btn.disabled || btn.dataset.processing === 'true'
    );
    if (isAnyProcessing) {
        return;
    }
    
    // Mark ALL buttons as processing and update UI
    const originalContents = new Map();
    allButtons.forEach(button => {
        originalContents.set(button, button.innerHTML);
        button.dataset.processing = 'true';
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    });
    
    // Send AJAX request
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: bookId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart badge immediately with response data if available
            if (data.cart_count !== undefined) {
                updateCartBadgeWithCount(data.cart_count);
            } else {
                // Fallback to API call
                updateCartBadge();
            }
            // Show success message
            showToast(data.message || 'Added to cart successfully!', 'success');
            
            // Success animation for ALL buttons
            allButtons.forEach(button => {
                button.innerHTML = '<i class="fas fa-check"></i> Added!';
                button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            });
            
            setTimeout(() => {
                allButtons.forEach(button => {
                    button.innerHTML = originalContents.get(button) || 'Add to Cart';
                    button.disabled = false;
                    button.style.background = '';
                    button.dataset.processing = 'false';
                });
            }, 2000);
        } else {
            showToast(data.message || 'Failed to add to cart', 'error');
            // Reset ALL buttons
            allButtons.forEach(button => {
                button.innerHTML = originalContents.get(button) || 'Add to Cart';
                button.disabled = false;
                button.dataset.processing = 'false';
            });
        }
    })
    .catch(error => {
        showToast('Network error. Please try again.', 'error');
        // Reset ALL buttons
        allButtons.forEach(button => {
            button.innerHTML = originalContents.get(button) || 'Add to Cart';
            button.disabled = false;
            button.dataset.processing = 'false';
        });
    });
};

// Helper Functions
function isAuthenticated() {
    // Check if user is logged in
    return document.querySelector('[data-user-authenticated]') !== null || 
           document.body.classList.contains('authenticated') ||
           document.querySelector('meta[name="user-authenticated"]') !== null;
}

function showLoginPrompt() {
    if (confirm('You need to login to perform this action. Would you like to login now?')) {
        window.location.href = '/login';
    }
}

function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas ${getToastIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getToastColor(type)};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after delay
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

function getToastIcon(type) {
    switch(type) {
        case 'success': return 'fa-check-circle';
        case 'error': return 'fa-exclamation-circle';
        case 'warning': return 'fa-exclamation-triangle';
        default: return 'fa-info-circle';
    }
}

function getToastColor(type) {
    switch(type) {
        case 'success': return '#10b981';
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        default: return '#3b82f6';
    }
}

// Unified cart badge update function that accepts count directly
function updateCartBadgeWithCount(count) {
    const badges = document.querySelectorAll('.cart-badge, .cart-count');
    badges.forEach(badge => {
        badge.textContent = count || 0;
        badge.style.display = count > 0 ? 'inline' : 'none';
        // Animate badge
        badge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 200);
    });
}

// Update cart badge via API call (fallback)
function updateCartBadge() {
    fetch('/cart/data', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.data) {
            updateCartBadgeWithCount(data.data.count);
        }
    })
    .catch(error => {
        // Silent fail for badge update
    });
}

// Carousel control helpers to prevent conflicts
function pauseCarouselTemporary() {
    // Access global carousel instance if available
    if (window.carouselInstance && window.carouselInstance.autoSlideInterval) {
        clearInterval(window.carouselInstance.autoSlideInterval);
        window.carouselInstance.autoSlideInterval = null;
        
        // Resume after short delay
        setTimeout(() => {
            if (window.carouselInstance && !window.carouselInstance.autoSlideInterval) {
                window.carouselInstance.autoSlideInterval = setInterval(() => {
                    if (typeof nextSlide === 'function') {
                        nextSlide();
                    }
                }, 6000);
            }
        }, 2000);
    }
}

// Make the update function globally available
window.updateCartBadgeWithCount = updateCartBadgeWithCount;
window.updateCartBadge = updateCartBadge;
window.pauseCarouselTemporary = pauseCarouselTemporary;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set authentication flag
    const userElement = document.querySelector('[data-user-authenticated]');
    if (userElement) {
        document.body.classList.add('authenticated');
    }
    
    // Update cart badge on page load for authenticated users
    if (isAuthenticated()) {
        updateCartBadge();
    }
    
    // Use event delegation to prevent duplicate handlers on dynamically added elements
    let bookCardEventsDelegated = false;
    
    if (!bookCardEventsDelegated) {
        // Delegate cart button events - IMPROVED with clicked button
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-book-id].btn-add-to-cart, [data-book-id].btn-add-to-cart *')) {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                e.stopImmediatePropagation(); // Prevent multiple handlers
                
                const button = e.target.closest('[data-book-id].btn-add-to-cart');
                if (button) {
                    const bookId = button.getAttribute('data-book-id');
                    if (bookId && button.dataset.processing !== 'true') {
                        addToCart(parseInt(bookId), button); // Pass clicked button
                    }
                }
            }
        }, { passive: false }); // Allow preventDefault
        
        // Delegate wishlist button events - IMPROVED with clicked button
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-book-id].btn-favorites, [data-book-id].btn-favorites *')) {
                e.preventDefault();
                e.stopPropagation(); // Prevent event bubbling
                e.stopImmediatePropagation(); // Prevent multiple handlers
                
                const button = e.target.closest('[data-book-id].btn-favorites');
                if (button) {
                    const bookId = button.getAttribute('data-book-id');
                    if (bookId) {
                        toggleWishlist(parseInt(bookId), button); // Pass clicked button
                    }
                }
            }
        }, { passive: false }); // Allow preventDefault
        
        bookCardEventsDelegated = true;
    }
});
