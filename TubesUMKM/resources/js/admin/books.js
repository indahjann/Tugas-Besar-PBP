/**
 * Admin Books/Products JavaScript
 * Handles product management interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Cover image preview functionality
    const coverUpload = document.getElementById('cover_upload');
    const coverUrl = document.getElementById('cover_image');
    
    if (coverUpload) {
        coverUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(e.target.result);
                };
                reader.readAsDataURL(file);
                
                // Clear URL input
                if (coverUrl) {
                    coverUrl.value = '';
                }
            }
        });
    }
    
    if (coverUrl) {
        coverUrl.addEventListener('input', function() {
            if (this.value) {
                // Clear file upload
                if (coverUpload) {
                    coverUpload.value = '';
                }
                // Show URL preview
                showImagePreview(this.value);
            }
        });
    }

    // Delete confirmation
    const deleteForms = document.querySelectorAll('form[method="POST"] button[type="submit"]');
    deleteForms.forEach(button => {
        if (button.classList.contains('text-red-600')) {
            button.closest('form').addEventListener('submit', function(e) {
                if (!confirm('Apakah Anda yakin ingin menghapus produk ini? Cover image juga akan dihapus.')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Stock warning
    const stockInput = document.getElementById('stock');
    if (stockInput) {
        stockInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            const feedback = document.createElement('p');
            
            // Remove old feedback
            const oldFeedback = this.parentElement.querySelector('.stock-feedback');
            if (oldFeedback) oldFeedback.remove();
            
            if (value === 0) {
                feedback.className = 'stock-feedback text-xs text-red-600 mt-1';
                feedback.textContent = '⚠️ Stok habis - produk tidak akan ditampilkan';
            } else if (value <= 10) {
                feedback.className = 'stock-feedback text-xs text-yellow-600 mt-1';
                feedback.textContent = '⚠️ Stok rendah - pertimbangkan untuk menambah stok';
            } else {
                feedback.className = 'stock-feedback text-xs text-green-600 mt-1';
                feedback.textContent = '✓ Stok cukup';
            }
            
            this.parentElement.appendChild(feedback);
        });
    }

    // Price formatter
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
                
                // Show formatted preview
                const preview = document.createElement('p');
                preview.className = 'text-xs text-gray-600 mt-1';
                preview.textContent = `Preview: ${formatted}`;
                
                const oldPreview = this.parentElement.querySelector('p.text-xs');
                if (oldPreview) oldPreview.remove();
                
                this.parentElement.appendChild(preview);
            }
        });
    }
});

/**
 * Show image preview
 */
function showImagePreview(src) {
    let preview = document.querySelector('.image-preview');
    
    if (!preview) {
        preview = document.createElement('div');
        preview.className = 'image-preview mt-4';
        
        const coverSection = document.querySelector('input[name="cover_upload"]')?.parentElement;
        if (coverSection) {
            coverSection.appendChild(preview);
        }
    }
    
    preview.innerHTML = `
        <p class="text-sm font-medium text-gray-700 mb-2">Preview Cover:</p>
        <img src="${src}" 
             alt="Cover Preview" 
             class="h-40 w-auto object-cover rounded shadow-md"
             onerror="this.src='https://via.placeholder.com/200x300?text=Invalid+Image'">
    `;
}

/**
 * Bulk actions (for future implementation)
 */
function bulkActivate() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    console.log('Activating', checkboxes.length, 'products');
    // TODO: Implement bulk activate
}

function bulkDeactivate() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    console.log('Deactivating', checkboxes.length, 'products');
    // TODO: Implement bulk deactivate
}

function bulkDelete() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    if (confirm(`Apakah Anda yakin ingin menghapus ${checkboxes.length} produk?`)) {
        console.log('Deleting', checkboxes.length, 'products');
        // TODO: Implement bulk delete
    }
}
