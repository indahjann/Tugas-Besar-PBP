/**
 * Wishlist Page Manager
 * Handles item removal and syncs state with other pages
 */

(function() {
    'use strict';

    // Check if we're on wishlist page
    const wishlistPage = document.querySelector('[data-page="wishlist"]');
    if (!wishlistPage) {
        console.log('[Wishlist] Not on wishlist page, skipping initialization');
        return;
    }

    const wishlistContainer = document.getElementById('wishlistContainer');
    if (!wishlistContainer) {
        console.log('[Wishlist] Container not found');
        return;
    }

    console.log('[Wishlist] Initializing wishlist page manager');

    // Initialize wishlist state from current items
    initWishlistStateFromDOM();

    // OVERRIDE: Handle wishlist button clicks on wishlist page
    // We need to intercept before categories.js handles it
    document.addEventListener('click', handleWishlistClick, true); // true = capture phase

    /**
     * Initialize sessionStorage from current DOM items
     */
    function initWishlistStateFromDOM() {
        const wishlistState = {};
        const bookCards = wishlistContainer.querySelectorAll('.book-card-modern');
        
        bookCards.forEach(card => {
            const btn = card.querySelector('.btn-favorites');
            if (btn) {
                const bookId = btn.getAttribute('data-book-id');
                if (bookId) {
                    wishlistState[bookId] = true;
                }
            }
        });
        
        sessionStorage.setItem('wishlistState', JSON.stringify(wishlistState));
        console.log('[Wishlist] Initialized state:', wishlistState);
    }

    /**
     * Handle wishlist button clicks
     */
    function handleWishlistClick(e) {
        const wishlistBtn = e.target.closest('.btn-favorites');
        if (!wishlistBtn) return;

        // Only handle if button is currently active (removing from wishlist)
        const isActive = wishlistBtn.classList.contains('active');
        if (!isActive) return; // Let categories.js handle adding

        const bookCard = wishlistBtn.closest('.book-card-modern');
        if (!bookCard) return;

        const bookId = wishlistBtn.getAttribute('data-book-id');
        console.log('[Wishlist] Removing book:', bookId);

        // Don't prevent default - let the toggle happen
        // But monitor the result

        // Use MutationObserver to watch for class changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const stillActive = wishlistBtn.classList.contains('active');
                    
                    if (!stillActive) {
                        console.log('[Wishlist] Button no longer active, removing card');
                        observer.disconnect();
                        removeCardFromDOM(bookCard, bookId);
                    }
                }
            });
        });

        // Start observing the button
        observer.observe(wishlistBtn, {
            attributes: true,
            attributeFilter: ['class']
        });

        // Safety timeout - disconnect observer after 3 seconds
        setTimeout(() => {
            observer.disconnect();
        }, 3000);
    }

    /**
     * Remove card from DOM with animation
     */
    function removeCardFromDOM(card, bookId) {
        console.log('[Wishlist] Removing card for book:', bookId);
        
        // Animate out
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';

        setTimeout(function() {
            card.remove();
            
            // Update sessionStorage
            updateSessionStorage(bookId, false);
            
            // Check if wishlist is now empty
            checkIfEmpty();
        }, 300);
    }

    /**
     * Update sessionStorage
     */
    function updateSessionStorage(bookId, isActive) {
        const wishlistState = JSON.parse(sessionStorage.getItem('wishlistState') || '{}');
        
        if (isActive) {
            wishlistState[bookId] = true;
        } else {
            delete wishlistState[bookId];
        }
        
        sessionStorage.setItem('wishlistState', JSON.stringify(wishlistState));
        console.log('[Wishlist] Updated sessionStorage:', wishlistState);
    }

    /**
     * Check if wishlist is empty
     */
    function checkIfEmpty() {
        const remainingCards = wishlistContainer.querySelectorAll('.book-card-modern');
        const count = remainingCards.length;
        
        console.log('[Wishlist] Remaining items:', count);
        
        if (count === 0) {
            showEmptyState();
        } else {
            updateResultsCount(count);
        }
    }

    /**
     * Update results count display
     */
    function updateResultsCount(count) {
        const resultsCount = document.querySelector('.results-count');
        if (resultsCount) {
            resultsCount.innerHTML = `Showing <strong>${count}</strong> of <strong>${count}</strong> books`;
        }
    }

    /**
     * Show empty wishlist state
     */
    function showEmptyState() {
        console.log('[Wishlist] Showing empty state');
        
        const booksContent = document.querySelector('.books-content');
        if (!booksContent) return;

        // Hide grid and results
        if (wishlistContainer) {
            wishlistContainer.style.display = 'none';
        }
        
        const resultsInfo = document.querySelector('.results-info');
        if (resultsInfo) {
            resultsInfo.style.display = 'none';
        }

        // Create and show empty state
        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.style.opacity = '0';
        emptyState.style.transition = 'opacity 0.3s ease';
        emptyState.innerHTML = `
            <i class="far fa-heart"></i>
            <h3>Your Wishlist is Empty</h3>
            <p>Start adding books you love!</p>
            <a href="${window.location.origin}/categories" class="btn-primary">
                <i class="fas fa-book"></i> Browse Books
            </a>
        `;
        
        booksContent.appendChild(emptyState);
        
        // Animate in
        setTimeout(() => {
            emptyState.style.opacity = '1';
        }, 100);
    }

    console.log('[Wishlist] Manager ready with MutationObserver');
})();