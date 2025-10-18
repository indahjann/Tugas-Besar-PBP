// Profile page functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile page loaded');
    
    // Debug: Log all forms on page
    const forms = document.querySelectorAll('form');
    console.log('Found forms:', forms.length);
    
    // Make sure cart.js is not loaded
    console.log('Cart module loaded?', typeof window.CartManager !== 'undefined' ? 'YES (BAD!)' : 'NO (GOOD!)');
    
    // Add submit event listeners to all forms for debugging
    forms.forEach((form, index) => {
        form.addEventListener('submit', function(e) {
            console.log(`Form ${index} submitting:`, {
                action: form.action,
                method: form.method,
                hasCSRF: !!form.querySelector('[name="_token"]')
            });
            // Let the form submit normally - don't prevent default
        });
    });
    
    // Log when buttons are clicked
    const buttons = document.querySelectorAll('button[type="submit"]');
    buttons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            console.log(`Submit button ${index} clicked`);
        });
    });
});
