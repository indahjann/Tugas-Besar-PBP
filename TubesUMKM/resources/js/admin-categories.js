// Admin Categories Management JavaScript

// Modal Functions
function openModal(mode, category = null) {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    const methodField = document.getElementById('methodField');
    
    // Reset form
    form.reset();
    
    if (mode === 'add') {
        // Add mode
        modalTitle.textContent = 'Tambah Kategori';
        submitText.textContent = 'Simpan';
        form.action = form.dataset.storeUrl;
        methodField.value = '';
        if (methodField.parentNode) {
            methodField.remove();
        }
    } else if (mode === 'edit' && category) {
        // Edit mode
        modalTitle.textContent = 'Edit Kategori';
        submitText.textContent = 'Update';
        
        // Set form action
        const updateUrl = form.dataset.updateUrl.replace('__ID__', category.id);
        form.action = updateUrl;
        
        // Add PUT method
        if (!methodField.parentNode) {
            form.insertBefore(methodField, form.firstChild);
        }
        methodField.value = 'PUT';
        
        // Fill form with category data
        document.getElementById('name').value = category.name;
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function handleModalBackdropClick(event) {
    if (event.target === event.currentTarget) {
        closeModal();
    }
}

// Make functions globally accessible
window.openModal = openModal;
window.closeModal = closeModal;
window.handleModalBackdropClick = handleModalBackdropClick;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Categories script loaded');
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('categoryModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeModal();
            }
        }
    });
    
    // Handle delete forms with confirmation
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const confirmMessage = form.dataset.confirm;
            if (!confirm(confirmMessage)) {
                event.preventDefault();
            }
        });
    });
    
    // Auto-hide success messages
    const alerts = document.querySelectorAll('.alert-fade');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000);
    });
});
