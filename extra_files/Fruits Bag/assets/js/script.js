// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');

    // Create mobile toggle button if it doesn't exist
    if (!document.querySelector('.sidebar-toggle')) {
        const sidebarToggle = document.createElement('button');
        sidebarToggle.className = 'sidebar-toggle btn btn-primary';
        sidebarToggle.innerHTML = '<i class="bi bi-list fs-4"></i>';
        document.body.appendChild(sidebarToggle);

        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (event) {
        const isClickInside = sidebar.contains(event.target) ||
            (document.querySelector('.sidebar-toggle') &&
                document.querySelector('.sidebar-toggle').contains(event.target));

        if (!isClickInside && window.innerWidth < 768 && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
        }
    });

    // Auto-hide notifications after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.classList.remove('show');
            setTimeout(function () {
                alert.remove();
            }, 150);
        }, 5000);
    });
});

// Format currency values
function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(value);
}

// Format date values
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Initialize all dropdowns
var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl)
});

// Add active class to current nav link and expand parent menu
document.addEventListener('DOMContentLoaded', function () {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar .nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath ||
            (currentPath.includes('pages') && href.includes(currentPath.split('/').pop()))) {
            link.classList.add('active');

            // If this link is in a sub-menu, expand the parent
            const parentItem = link.closest('.sub-menu');
            if (parentItem) {
                const parentLink = parentItem.previousElementSibling;
                if (parentLink) {
                    parentLink.classList.add('active');
                    // Expand the sub-menu
                    parentItem.style.maxHeight = parentItem.scrollHeight + 'px';

                    // Rotate chevron
                    const chevron = parentLink.querySelector('.bi-chevron-down');
                    if (chevron) {
                        chevron.style.transform = 'rotate(180deg)';
                    }
                }
            }
        }
    });
});

// Sidebar sub-menu toggle
document.addEventListener('DOMContentLoaded', function () {
    const navItems = document.querySelectorAll('.sidebar .nav-item');

    navItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        const subMenu = item.querySelector('.sub-menu');

        if (subMenu) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // Close other sub-menus
                navItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        const otherSubMenu = otherItem.querySelector('.sub-menu');
                        if (otherSubMenu) {
                            otherSubMenu.style.maxHeight = '0';

                            // Reset chevron
                            const otherChevron = otherItem.querySelector('.bi-chevron-down');
                            if (otherChevron) {
                                otherChevron.style.transform = 'rotate(0deg)';
                            }
                        }
                    }
                });

                // Toggle current sub-menu
                if (subMenu.style.maxHeight && subMenu.style.maxHeight !== '0px') {
                    subMenu.style.maxHeight = '0';

                    // Reset chevron
                    const chevron = link.querySelector('.bi-chevron-down');
                    if (chevron) {
                        chevron.style.transform = 'rotate(0deg)';
                    }
                } else {
                    subMenu.style.maxHeight = subMenu.scrollHeight + 'px';

                    // Rotate chevron
                    const chevron = link.querySelector('.bi-chevron-down');
                    if (chevron) {
                        chevron.style.transform = 'rotate(180deg)';
                    }
                }
            });
        }
    });
});

// Smooth scrolling for sidebar
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        // Enable smooth scrolling with mouse wheel
        sidebar.addEventListener('wheel', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const scrollAmount = e.deltaY > 0 ? 50 : -50;
            sidebar.scrollTop += scrollAmount;
        }, { passive: false });

        // Enable touch scrolling for mobile
        let isDown = false;
        let startY;
        let scrollTop;

        sidebar.addEventListener('touchstart', function (e) {
            isDown = true;
            startY = e.pageY - sidebar.offsetTop;
            scrollTop = sidebar.scrollTop;
        });

        sidebar.addEventListener('touchmove', function (e) {
            if (!isDown) return;
            e.preventDefault();
            const y = e.pageY - sidebar.offsetTop;
            const walk = (y - startY) * 2;
            sidebar.scrollTop = scrollTop - walk;
        });

        sidebar.addEventListener('touchend', function () {
            isDown = false;
        });
    }
});