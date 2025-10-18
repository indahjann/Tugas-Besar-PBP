/**
 * Categories Page JavaScript
 * Handles sorting, filtering, and wishlist interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('[Categories] Initializing...');
    
    // Initialize all features
    setupSortDropdown();
    setupBookCardInteractions();
    setupFilterOptions();
    setupMobileSidebar();
    
    // PENTING: Sync wishlist state from sessionStorage
    syncWishlistStateFromSession();
});

// TAMBAHKAN: Re-sync setiap kali page visible (user kembali ke tab/page ini)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        console.log('[Categories] Page visible, re-syncing wishlist state');
        syncWishlistStateFromSession();
    }
});

// TAMBAHKAN: Re-sync ketika window focus (user kembali dari wishlist page)
window.addEventListener('focus', function() {
    console.log('[Categories] Window focused, re-syncing wishlist state');
    syncWishlistStateFromSession();
});

// TAMBAHKAN: Re-sync dari pageshow event (detects back/forward navigation)
window.addEventListener('pageshow', function(event) {
    // event.persisted true = page loaded from cache (back button)
    if (event.persisted) {
        console.log('[Categories] Page loaded from cache, re-syncing wishlist state');
        syncWishlistStateFromSession();
    }
});

/**
 * Sync wishlist button states from sessionStorage
 * This ensures buttons reflect the actual wishlist state
 */
function syncWishlistStateFromSession() {
    const wishlistState = JSON.parse(sessionStorage.getItem('wishlistState') || '{}');
    console.log('[Categories] Syncing wishlist state:', wishlistState);
    
    document.querySelectorAll('.btn-favorites').forEach(btn => {
        const bookId = btn.getAttribute('data-book-id');
        const shouldBeActive = wishlistState[bookId] === true;
        const icon = btn.querySelector('i');
        
        console.log(`[Categories] Book ${bookId}: shouldBeActive=${shouldBeActive}, currentlyActive=${btn.classList.contains('active')}`);
        
        if (shouldBeActive) {
            // Should be in wishlist
            if (!btn.classList.contains('active')) {
                btn.classList.add('active');
            }
            if (icon) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            }
        } else {
            // Should NOT be in wishlist
            if (btn.classList.contains('active')) {
                btn.classList.remove('active');
            }
            if (icon) {
                icon.classList.remove('fas');
                icon.classList.add('far');
            }
        }
    });
}

/**
 * Setup sort dropdown functionality
 */
function setupSortDropdown() {
    const sortSelect = document.getElementById('sortSelect');
    if (!sortSelect) return;
    
    sortSelect.addEventListener('change', function() {
        const currentUrl = new URL(window.location);
        const sortValue = this.value;
        
        currentUrl.searchParams.set('sort', sortValue);
        window.location.href = currentUrl.toString();
    });
}

/**
 * Setup book card interactions (Add to Cart & Wishlist)
 */
function setupBookCardInteractions() {
    // Wishlist buttons
    document.querySelectorAll('.btn-favorites').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleWishlist(this);
        });
    });
    
    // Add to Cart buttons (handled by cart.js)
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
        });
    });
}

/**
 * Toggle wishlist for a book
 */
function toggleWishlist(button) {
    const bookId = button.getAttribute('data-book-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!bookId) {
        showNotification('Error: Book ID not found', 'error');
        return;
    }
    
    if (!csrfToken) {
        showNotification('Error: CSRF token not found', 'error');
        return;
    }
    
    // Save original state
    const wasActive = button.classList.contains('active');
    const icon = button.querySelector('i');
    
    // Show loading
    button.disabled = true;
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    console.log('[Categories] Toggling wishlist for book:', bookId, 'wasActive:', wasActive);
    
    // Make request
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ book_id: bookId })
    })
    .then(response => response.json())
    .then(data => {
        console.log('[Categories] Toggle response:', data);
        
        if (data.success) {
            // Update button UI
            if (data.action === 'added') {
                button.classList.add('active');
                button.innerHTML = '<i class="fas fa-heart"></i>';
                button.title = 'Remove from favorites';
                updateSessionStorage(bookId, true);
                showNotification(data.message || 'Added to wishlist', 'success');
            } else {
                button.classList.remove('active');
                button.innerHTML = '<i class="far fa-heart"></i>';
                button.title = 'Add to favorites';
                updateSessionStorage(bookId, false);
                showNotification(data.message || 'Removed from wishlist', 'info');
            }
        } else {
            // Revert on error
            button.innerHTML = originalHTML;
            showNotification(data.message || 'Failed to update wishlist', 'error');
        }
    })
    .catch(error => {
        console.error('[Categories] Wishlist error:', error);
        button.innerHTML = originalHTML;
        showNotification('Failed to update wishlist', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}

function updateSessionStorage(bookId, isActive) {
    const wishlistState = JSON.parse(sessionStorage.getItem('wishlistState') || '{}');
    
    if (isActive) {
        wishlistState[bookId] = true;
    } else {
        delete wishlistState[bookId];
    }
    
    sessionStorage.setItem('wishlistState', JSON.stringify(wishlistState));
    console.log('[Categories] Updated sessionStorage:', wishlistState);
}

function setupFilterOptions() {
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            const sortText = this.textContent.trim();
            showNotification(`Sorting by: ${sortText}`, 'info');
        });
    });
}

function setupMobileSidebar() {
    if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.categories-sidebar');
        if (sidebar && !document.querySelector('.mobile-sidebar-toggle')) {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'mobile-sidebar-toggle btn btn-outline-primary mb-3';
            toggleBtn.innerHTML = '<i class="fas fa-filter"></i> Filters & Categories';
            
            const booksContent = document.querySelector('.books-content');
            if (booksContent) {
                booksContent.parentNode.insertBefore(toggleBtn, booksContent);
                
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-show');
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('mobile-show')) {
                        icon.classList.replace('fa-filter', 'fa-times');
                    } else {
                        icon.classList.replace('fa-times', 'fa-filter');
                    }
                });
            }
        }
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const iconMap = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'info': 'info-circle'
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

window.addEventListener('resize', function() {
    const sidebar = document.querySelector('.categories-sidebar');
    const toggleBtn = document.querySelector('.mobile-sidebar-toggle');
    
    if (window.innerWidth > 768) {
        if (sidebar) sidebar.classList.remove('mobile-show');
        if (toggleBtn) toggleBtn.style.display = 'none';
    } else {
        if (toggleBtn) toggleBtn.style.display = 'block';
    }
});

const notificationStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(400px);
        transition: transform 0.3s ease;
        z-index: 9999;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .notification-success i { color: #10b981; }
    .notification-error i { color: #ef4444; }
    .notification-info i { color: #3b82f6; }
    
    @media (max-width: 768px) {
        .notification {
            right: 10px;
            left: 10px;
            top: 10px;
        }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);