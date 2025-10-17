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
import './cart';
import './book-detail';
import './checkout';
import './orders';

// Admin scripts (only loaded on admin pages)
if (window.location.pathname.startsWith('/admin')) {
    import('./admin/admin');
}
