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
    
    console.log('[Wishlist] Initializing wishlist page manager');

    // Initialize wishlist state from current items
    if (wishlistContainer) {
        initWishlistStateFromDOM();
        // OVERRIDE: Handle wishlist button clicks on wishlist page
        document.addEventListener('click', handleWishlistClick, true);
    } else {
        // Container tidak ada = sudah empty state dari server
        console.log('[Wishlist] Container not found - already empty');
    }

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

        const isActive = wishlistBtn.classList.contains('active');
        if (!isActive) return;

        const bookCard = wishlistBtn.closest('.book-card-modern');
        if (!bookCard) return;

        const bookId = wishlistBtn.getAttribute('data-book-id');
        console.log('[Wishlist] Removing book:', bookId);

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

        observer.observe(wishlistBtn, {
            attributes: true,
            attributeFilter: ['class']
        });

        setTimeout(() => observer.disconnect(), 3000);
    }

    /**
     * Remove card from DOM with animation
     */
    function removeCardFromDOM(card, bookId) {
        console.log('[Wishlist] Removing card for book:', bookId);
        
        card.style.transition = 'all 0.3s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';

        setTimeout(function() {
            card.remove();
            updateSessionStorage(bookId, false);
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
        if (!wishlistContainer) return;
        
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

    function showEmptyState() {
        console.log('[Wishlist] Showing empty state');
        
        const booksContent = document.querySelector('.books-content');
        if (!booksContent) return;

        booksContent.innerHTML = '';

        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state';
        emptyState.style.opacity = '0';
        emptyState.style.transition = 'opacity 0.3s ease';
        
        const baseUrl = window.location.origin;
        
        emptyState.innerHTML = `
            <i class="far fa-heart"></i>
            <h3>Wishlist Kamu Kosong</h3>
            <p>Mulai tambahkan buku yang kamu suka!</p>
            <a href="${baseUrl}/categories" class="btn-primary">
                Jelajahi Buku!
            </a>
        `;
        
        booksContent.appendChild(emptyState);
        
        setTimeout(() => {
            emptyState.style.opacity = '1';
        }, 100);
    }
    
    console.log('[Wishlist] Manager ready with MutationObserver');
})();