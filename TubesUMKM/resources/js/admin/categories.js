/**
 * Admin Categories JavaScript
 * Handles category modal and interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    initCategoryModal();
    initDeleteConfirmation();
});

/**
 * Initialize category modal functionality
 */
function initCategoryModal() {
    // Expose functions globally for onclick handlers
    window.openModal = openModal;
    window.closeModal = closeModal;
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

/**
 * Open modal for add or edit mode
 * @param {string} mode - 'add' or 'edit'
 * @param {object|null} data - Category data for edit mode
 */
function openModal(mode, data = null) {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    const methodField = document.getElementById('methodField');
    const nameInput = document.getElementById('name');
    const descInput = document.getElementById('description');
    
    if (!modal || !form) return;
    
    if (mode === 'add') {
        // Add mode - empty form
        title.textContent = 'Tambah Kategori';
        submitText.textContent = 'Simpan';
        form.action = form.dataset.storeUrl;
        methodField.value = '';
        nameInput.value = '';
        descInput.value = '';
    } else if (mode === 'edit' && data) {
        // Edit mode - fill form with data
        title.textContent = 'Edit Kategori';
        submitText.textContent = 'Update';
        form.action = form.dataset.updateUrl.replace('__ID__', data.id);
        methodField.value = 'PUT';
        nameInput.value = data.name || '';
        descInput.value = data.description || '';
    }
    
    // Show modal and focus input
    modal.classList.remove('hidden');
    setTimeout(() => nameInput.focus(), 100);
}

/**
 * Close category modal
 */
function closeModal() {
    const modal = document.getElementById('categoryModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Initialize delete confirmation
 */
function initDeleteConfirmation() {
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.dataset.confirm;
            if (message && !confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Handle modal backdrop click
 */
function handleModalBackdropClick(event) {
    if (event.target.id === 'categoryModal') {
        closeModal();
    }
}
