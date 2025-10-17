/**
 * Admin Orders JavaScript
 * Handles admin order management interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success messages after 5 seconds
    const successAlerts = document.querySelectorAll('.admin-alert-success');
    successAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Confirmation for status changes
    const statusForms = document.querySelectorAll('form[action*="status"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="status"]');
            const newStatus = select.options[select.selectedIndex].text;
            
            if (!confirm(`Apakah Anda yakin ingin mengubah status pesanan menjadi "${newStatus}"?`)) {
                e.preventDefault();
            }
        });
    });

    // Filter form auto-submit on status change (optional)
    const statusFilter = document.querySelector('#status');
    if (statusFilter && statusFilter.closest('form').querySelector('button[type="submit"]')) {
        // Status filter is manual submit, no auto-submit needed
    }

    // Add loading state to filter button
    const filterBtn = document.querySelector('button[type="submit"]');
    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-search');
                icon.classList.add('fa-spinner', 'fa-spin');
            }
        });
    }

    // Highlight row on hover for better UX
    const tableRows = document.querySelectorAll('.admin-table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f9fafb';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});

/**
 * Export orders data (for future implementation)
 */
function exportOrders(format = 'csv') {
    console.log('Exporting orders as', format);
    // TODO: Implement export functionality
}

/**
 * Print order details (for future implementation)
 */
function printOrder(orderId) {
    console.log('Printing order', orderId);
    // TODO: Implement print functionality
}
