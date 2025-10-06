// Navigation Bar JavaScript for BUKUKU Bookstore

class BukukuNavbar {
    constructor() {
        this.init();
        this.setupEventListeners();
        this.setupScrollEffect();
        this.setupSearch();
    }

    init() {
        this.navbar = document.querySelector('.bukuku-navbar');
        this.mobileToggle = document.querySelector('.mobile-toggle');
        this.mobileMenu = document.querySelector('.mobile-menu');
        this.userDropdown = document.querySelector('.user-dropdown');
        this.searchInput = document.querySelector('.search-input');
        this.cartIcon = document.querySelector('.cart-icon');
        this.cartBadge = document.querySelector('.cart-badge');
        
        this.isSearchOpen = false;
        this.cartCount = this.getCartCount();
        this.updateCartBadge();
    }

    setupEventListeners() {
        // Mobile menu toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleMobileMenu();
            });
        }

        // User dropdown toggle
        if (this.userDropdown) {
            const userTrigger = this.userDropdown.querySelector('.user-trigger');
            const dropdownMenu = this.userDropdown.querySelector('.dropdown-menu');
            
            if (userTrigger && dropdownMenu) {
                userTrigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleUserDropdown();
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.userDropdown.contains(e.target)) {
                        this.closeUserDropdown();
                    }
                });
            }
        }

        // Search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.performSearch();
                }
            });

            this.searchInput.addEventListener('focus', () => {
                this.openSearchSuggestions();
            });
        }

        // Cart functionality
        if (this.cartIcon) {
            this.cartIcon.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleCart();
            });
        }

        // Navigation links with AJAX
        this.setupAjaxNavigation();

        // Escape key to close dropdowns
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllDropdowns();
            }
        });
    }

    setupScrollEffect() {
        let lastScroll = 0;
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            // Add/remove scrolled class for styling effects
            if (currentScroll > 50) {
                this.navbar?.classList.add('scrolled');
            } else {
                this.navbar?.classList.remove('scrolled');
            }
            
            // Keep navbar always visible - disable hide/show
            // this.navbar?.style.setProperty('transform', 'translateY(0)');
            
            lastScroll = currentScroll;
        });
    }

    setupSearch() {
        // Search suggestions functionality
        let searchTimeout;
        
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        this.fetchSearchSuggestions(query);
                    }, 300);
                } else {
                    this.hideSearchSuggestions();
                }
            });
        }
    }

    setupAjaxNavigation() {
        // Setup AJAX navigation for smooth page transitions
        const navLinks = document.querySelectorAll('[data-ajax-link]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = link.getAttribute('href');
                const title = link.textContent.trim();
                
                this.loadPageContent(url, title);
                this.updateActiveNavLink(link);
            });
        });
    }

    async loadPageContent(url, title) {
        try {
            // Show loading state
            this.showLoadingState();
            
            const response = await fetch(url + '?ajax=1', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.text();
            
            // Create a temporary div to parse the response
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;
            
            // Extract only the main content, not navbar/footer
            const newMainContent = tempDiv.querySelector('#main-content, main, .main-content');
            const contentArea = document.querySelector('#main-content');
            
            if (contentArea && newMainContent) {
                contentArea.innerHTML = newMainContent.innerHTML;
                
                // Update browser history
                window.history.pushState({ url, title }, title, url);
                document.title = title + ' - BUKUKU';
                
                // Reinitialize any JavaScript components in new content
                this.reinitializeComponents();
            } else {
                // If we can't extract main content, fallback to normal navigation
                throw new Error('Could not extract main content');
            }
            
            this.hideLoadingState();
        } catch (error) {
            console.error('Error loading page content:', error);
            this.hideLoadingState();
            
            // Fallback to normal navigation
            window.location.href = url;
        }
    }

    showLoadingState() {
        const contentArea = document.querySelector('#main-content');
        if (contentArea) {
            contentArea.style.opacity = '0.5';
            contentArea.style.pointerEvents = 'none';
        }
        
        // Add loading spinner
        this.showLoadingSpinner();
    }

    hideLoadingState() {
        const contentArea = document.querySelector('#main-content');
        if (contentArea) {
            contentArea.style.opacity = '1';
            contentArea.style.pointerEvents = 'auto';
        }
        
        this.hideLoadingSpinner();
    }

    showLoadingSpinner() {
        if (!document.querySelector('.page-loader')) {
            const loader = document.createElement('div');
            loader.className = 'page-loader';
            loader.innerHTML = `
                <div class="loader-spinner">
                    <i class="fas fa-book fa-spin"></i>
                    <span>Loading...</span>
                </div>
            `;
            document.body.appendChild(loader);
        }
    }

    hideLoadingSpinner() {
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.remove();
        }
    }

    updateActiveNavLink(activeLink) {
        // Remove active class from all nav links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
        });
        
        // Add active class to current link
        activeLink.classList.add('active');
    }

    reinitializeComponents() {
        // Reinitialize components that might be in the new content
        // This is where you'd reinitialize carousels, modals, etc.
        
        // Trigger custom event for other components
        window.dispatchEvent(new CustomEvent('contentLoaded'));
    }

    toggleMobileMenu() {
        if (this.mobileMenu) {
            this.mobileMenu.classList.toggle('show');
            
            // Update mobile toggle icon
            const icon = this.mobileToggle?.querySelector('i');
            if (icon) {
                if (this.mobileMenu.classList.contains('show')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            }
        }
    }

    toggleUserDropdown() {
        const dropdownMenu = this.userDropdown?.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.classList.toggle('show');
        }
    }

    closeUserDropdown() {
        const dropdownMenu = this.userDropdown?.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.classList.remove('show');
        }
    }

    closeAllDropdowns() {
        this.closeUserDropdown();
        this.hideSearchSuggestions();
        
        if (this.mobileMenu) {
            this.mobileMenu.classList.remove('show');
        }
    }

    async performSearch() {
        const query = this.searchInput?.value.trim();
        if (!query) return;

        try {
            const response = await fetch(`/search?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                const results = await response.json();
                this.displaySearchResults(results);
            }
        } catch (error) {
            console.error('Search error:', error);
        }
    }

    async fetchSearchSuggestions(query) {
        try {
            const response = await fetch(`/search/suggestions?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                const suggestions = await response.json();
                this.displaySearchSuggestions(suggestions);
            }
        } catch (error) {
            console.error('Search suggestions error:', error);
        }
    }

    displaySearchSuggestions(suggestions) {
        // Create or update search suggestions dropdown
        let suggestionsContainer = document.querySelector('.search-suggestions');
        
        if (!suggestionsContainer) {
            suggestionsContainer = document.createElement('div');
            suggestionsContainer.className = 'search-suggestions';
            
            const searchBar = document.querySelector('.search-bar');
            if (searchBar) {
                searchBar.appendChild(suggestionsContainer);
            }
        }

        if (suggestions.length > 0) {
            suggestionsContainer.innerHTML = suggestions.map(suggestion => `
                <div class="suggestion-item" data-value="${suggestion.title}">
                    <i class="fas fa-search"></i>
                    <span>${suggestion.title}</span>
                </div>
            `).join('');

            suggestionsContainer.classList.add('show');

            // Add click handlers for suggestions
            suggestionsContainer.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    const value = e.currentTarget.dataset.value;
                    if (this.searchInput) {
                        this.searchInput.value = value;
                        this.performSearch();
                    }
                    this.hideSearchSuggestions();
                });
            });
        } else {
            this.hideSearchSuggestions();
        }
    }

    hideSearchSuggestions() {
        const suggestionsContainer = document.querySelector('.search-suggestions');
        if (suggestionsContainer) {
            suggestionsContainer.classList.remove('show');
        }
    }

    openSearchSuggestions() {
        const suggestionsContainer = document.querySelector('.search-suggestions');
        if (suggestionsContainer && suggestionsContainer.children.length > 0) {
            suggestionsContainer.classList.add('show');
        }
    }

    displaySearchResults(results) {
        // This would typically navigate to a search results page
        // or update the main content area with results
        this.loadPageContent(`/search?q=${encodeURIComponent(this.searchInput.value)}`, 'Search Results');
    }

    toggleCart() {
        // Toggle cart sidebar or modal
        const cartSidebar = document.querySelector('.cart-sidebar');
        if (cartSidebar) {
            cartSidebar.classList.toggle('show');
        } else {
            // If no cart sidebar exists, navigate to cart page
            this.loadPageContent('/cart', 'Shopping Cart');
        }
    }

    getCartCount() {
        // Get cart count from localStorage, session, or API
        const cartItems = JSON.parse(localStorage.getItem('cart') || '[]');
        return cartItems.reduce((total, item) => total + (item.quantity || 1), 0);
    }

    updateCartBadge() {
        if (this.cartBadge) {
            if (this.cartCount > 0) {
                this.cartBadge.textContent = this.cartCount;
                this.cartBadge.style.display = 'flex';
            } else {
                this.cartBadge.style.display = 'none';
            }
        }
    }

    updateCartCount(newCount) {
        this.cartCount = newCount;
        this.updateCartBadge();
    }
}

// Initialize navbar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Prevent duplicate navbar initialization
    if (!window.bukukuNavbar) {
        window.bukukuNavbar = new BukukuNavbar();
    }
});

// Handle browser back/forward buttons
window.addEventListener('popstate', (e) => {
    if (e.state && e.state.url) {
        window.bukukuNavbar.loadPageContent(e.state.url, e.state.title);
    }
});