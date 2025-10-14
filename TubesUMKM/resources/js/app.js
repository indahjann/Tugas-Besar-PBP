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
