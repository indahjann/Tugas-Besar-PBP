/**
 * Admin Panel JavaScript
 * Handles common admin functionality
 */

// Import admin modules
import './categories.js';

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success/info alerts after 5 seconds
    const alerts = document.querySelectorAll('.bg-green-100.border, .bg-blue-100.border');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Image preview for file uploads
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result, input);
                };
                reader.readAsDataURL(file);
                
                // Clear URL input if exists
                const urlInput = document.getElementById('cover_image');
                if (urlInput) urlInput.value = '';
            }
        });
    });

    // URL input clears file upload
    const urlInput = document.getElementById('cover_image');
    if (urlInput) {
        urlInput.addEventListener('input', function() {
            if (this.value) {
                const fileInput = document.getElementById('cover_upload');
                if (fileInput) fileInput.value = '';
            }
        });
    }

    // Status change confirmation
    const statusForms = document.querySelectorAll('form[action*="status"]');
    statusForms.forEach(form => {
        const select = form.querySelector('select[name="status"]');
        if (select) {
            const initialValue = select.value;
            form.addEventListener('submit', function(e) {
                if (select.value !== initialValue) {
                    const newStatus = select.options[select.selectedIndex].text;
                    if (!confirm(`Ubah status pesanan menjadi "${newStatus}"?`)) {
                        e.preventDefault();
                    }
                }
            });
        }
    });

    // Stock level indicator
    const stockInput = document.getElementById('stock');
    if (stockInput) {
        stockInput.addEventListener('input', function() {
            updateStockIndicator(this);
        });
        updateStockIndicator(stockInput);
    }
});

/**
 * Show image preview for file uploads
 */
function showImagePreview(src, input) {
    let preview = input.parentElement.querySelector('.image-preview');
    
    if (!preview) {
        preview = document.createElement('div');
        preview.className = 'image-preview mt-3';
        input.parentElement.appendChild(preview);
    }
    
    preview.innerHTML = `
        <p class="text-sm font-medium text-gray-700 mb-2">Preview Cover:</p>
        <img src="${src}" 
             alt="Preview" 
             class="h-40 w-auto object-cover rounded shadow-md"
             onerror="this.src='https://via.placeholder.com/200x300?text=Invalid+Image'">
    `;
}

/**
 * Update stock level indicator
 */
function updateStockIndicator(input) {
    const value = parseInt(input.value) || 0;
    let indicator = input.parentElement.querySelector('.stock-indicator');
    
    if (!indicator) {
        indicator = document.createElement('p');
        indicator.className = 'stock-indicator text-xs mt-1';
        input.parentElement.appendChild(indicator);
    }
    
    if (value === 0) {
        indicator.className = 'stock-indicator text-xs mt-1 text-red-600';
        indicator.innerHTML = '⚠️ Stok habis - produk tidak ditampilkan';
    } else if (value <= 10) {
        indicator.className = 'stock-indicator text-xs mt-1 text-yellow-600';
        indicator.innerHTML = '⚠️ Stok rendah';
    } else {
        indicator.className = 'stock-indicator text-xs mt-1 text-green-600';
        indicator.innerHTML = '✓ Stok tersedia';
    }
}

