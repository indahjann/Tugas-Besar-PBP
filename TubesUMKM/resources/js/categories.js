// Categories Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Sort By Dropdown
    setupSortDropdown();
    
    // Book Cards Interactions
    setupBookCardInteractions();
    
    // Filter Options
    setupFilterOptions();
    
    // Mobile Sidebar Toggle (for smaller screens)
    setupMobileSidebar();
});

function setupSortDropdown() {
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location);
            const sortValue = this.value;
            
            // Update URL parameter
            currentUrl.searchParams.set('sort', sortValue);
            
            // Redirect to new URL with sort parameter
            window.location.href = currentUrl.toString();
        });
    }
}

function setupBookCardInteractions() {
    // Add to Cart buttons
    document.querySelectorAll('.btn-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bookCard = this.closest('.book-card');
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            
            // Add loading state
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i>';
                showNotification(`"${bookTitle}" added to cart!`, 'success');
                
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                }, 1000);
            }, 500);
        });
    });
    
    // Wishlist buttons
    document.querySelectorAll('.btn-wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            const bookCard = this.closest('.book-card');
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#e74c3c';
                showNotification(`"${bookTitle}" added to wishlist!`, 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
                showNotification(`"${bookTitle}" removed from wishlist!`, 'info');
            }
        });
    });
    
    // Preview buttons
    document.querySelectorAll('.btn-preview').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const bookCard = this.closest('.book-card');
            const bookTitle = bookCard.querySelector('.book-title').textContent;
            
            // Simple modal simulation
            showNotification(`Quick preview for "${bookTitle}" - Feature coming soon!`, 'info');
        });
    });
}

function setupFilterOptions() {
    document.querySelectorAll('.filter-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            document.querySelectorAll('.filter-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Get sort option
            const sortText = this.textContent.trim();
            showNotification(`Sorting by: ${sortText}`, 'info');
            
            // Here you would implement actual sorting logic
            // For demo purposes, we'll just show the notification
        });
    });
}

function setupMobileSidebar() {
    // Create mobile toggle button if needed
    if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.categories-sidebar');
        if (sidebar && !document.querySelector('.mobile-sidebar-toggle')) {
            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'mobile-sidebar-toggle btn btn-outline-primary mb-3';
            toggleBtn.innerHTML = '<i class="fas fa-filter"></i> Filters & Categories';
            
            const booksContent = document.querySelector('.books-content');
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

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Hide and remove notification
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Handle window resize
window.addEventListener('resize', function() {
    const sidebar = document.querySelector('.categories-sidebar');
    const toggleBtn = document.querySelector('.mobile-sidebar-toggle');
    
    if (window.innerWidth > 768) {
        // Desktop view
        if (sidebar) {
            sidebar.classList.remove('mobile-show');
        }
        if (toggleBtn) {
            toggleBtn.style.display = 'none';
        }
    } else {
        // Mobile view
        if (toggleBtn) {
            toggleBtn.style.display = 'block';
        }
    }
});

// Add CSS for notifications
const notificationStyles = `
    .notification {
        position: fixed;
        top: 5rem;
        right: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
        border-left: 4px solid #667eea;
    }
    
    .notification.notification-success {
        border-left-color: #10b981;
    }
    
    .notification.notification-error {
        border-left-color: #ef4444;
    }
    
    .notification.notification-info {
        border-left-color: #3b82f6;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #374151;
        font-weight: 500;
    }
    
    .notification-success .notification-content i {
        color: #10b981;
    }
    
    .notification-error .notification-content i {
        color: #ef4444;
    }
    
    .notification-info .notification-content i {
        color: #3b82f6;
    }
    
    .mobile-sidebar-toggle {
        display: none;
    }
    
    @media (max-width: 768px) {
        .categories-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            z-index: 9999;
            transition: left 0.3s ease;
            overflow-y: auto;
        }
        
        .categories-sidebar.mobile-show {
            left: 0;
        }
        
        .mobile-sidebar-toggle {
            display: block !important;
        }
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);