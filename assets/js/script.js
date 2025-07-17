document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content').classList.toggle('active');
    });
    
    // Auto-collapse sidebar on small screens
    if (window.innerWidth < 768) {
        document.getElementById('sidebar').classList.add('active');
    }
    
    // Update active menu item
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
    const menuItems = document.querySelectorAll('#sidebar a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href').includes(currentPage)) {
            item.parentElement.classList.add('active');
            
            // Expand parent dropdown if exists
            const parentDropdown = item.closest('.collapse');
            if (parentDropdown) {
                parentDropdown.classList.add('show');
                const dropdownToggle = parentDropdown.previousElementSibling;
                if (dropdownToggle) {
                    dropdownToggle.setAttribute('aria-expanded', 'true');
                    dropdownToggle.classList.remove('collapsed');
                }
            }
        }
    });
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});