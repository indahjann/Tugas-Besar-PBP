import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Component scripts
import './components/navbar';
import './components/footer';
import './components/book-card';

// App-specific scripts
import './carousel';
import './categories';
import './book-detail';
import './checkout';
import './orders';
import './profile';

// Conditional imports
const currentPath = window.location.pathname;

// Only load cart.js on relevant pages
if (!currentPath.includes('/profile') && !currentPath.includes('/admin')) {
    import('./cart');
}

// Admin scripts (only loaded on admin pages)
if (window.location.pathname.startsWith('/admin')) {
    import('./admin/admin');
}
