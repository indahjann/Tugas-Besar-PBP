import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Component scripts
import './components/navbar';
import './components/footer';

// App-specific scripts
import './carousel';
import './categories';
