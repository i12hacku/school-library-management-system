document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navMain = document.getElementById('navMain');
    
    mobileToggle.addEventListener('click', function() {
        navMain.classList.toggle('active');
        this.innerHTML = navMain.classList.contains('active') ? 
            '<i class="bi bi-x"></i>' : '<i class="bi bi-list"></i>';
    });
    
    // Handle dropdown menus on mobile
    const dropdownToggles = document.querySelectorAll('.dropdown > a');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                const dropdown = this.parentElement;
                dropdown.classList.toggle('active');
                
                // Close other open dropdowns
                document.querySelectorAll('.dropdown').forEach(item => {
                    if (item !== dropdown) {
                        item.classList.remove('active');
                    }
                });
            }
        });
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 && 
            !e.target.closest('#navMain') && 
            !e.target.closest('#mobileToggle')) {
            navMain.classList.remove('active');
            mobileToggle.innerHTML = '<i class="bi bi-list"></i>';
            document.querySelectorAll('.dropdown').forEach(item => {
                item.classList.remove('active');
            });
        }
    });
    
    // Highlight current page in navigation
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage || 
            link.getAttribute('href').includes(currentPage)) {
            link.classList.add('active');
            
            // Highlight parent dropdown if exists
            const parentDropdown = link.closest('.dropdown');
            if (parentDropdown) {
                parentDropdown.querySelector('> a').classList.add('active');
            }
        }
    });
    
    // Add shadow to header on scroll
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        } else {
            header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
        }
    });
});