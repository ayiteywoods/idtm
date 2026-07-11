import './page-builder';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mobileToggle = document.getElementById('sidebar-toggle');
    const collapseBtn = document.getElementById('sidebar-collapse-btn');
    const expandBtn = document.getElementById('sidebar-expand-btn');
    const userMenu = document.getElementById('user-menu');
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    const COLLAPSE_KEY = 'portal-sidebar-collapsed';

    const isDesktop = () => window.innerWidth >= 1024;

    const setSidebarCollapsed = (collapsed) => {
        if (!sidebar) return;

        sidebar.classList.toggle('is-collapsed', collapsed);
        document.body.classList.toggle('sidebar-collapsed', collapsed);

        if (expandBtn) {
            expandBtn.classList.toggle('hidden', !collapsed);
            expandBtn.classList.toggle('lg:inline-flex', collapsed);
        }

        if (collapseBtn) {
            collapseBtn.classList.toggle('hidden', collapsed);
            collapseBtn.classList.toggle('lg:inline-flex', !collapsed);
        }

        if (isDesktop()) {
            localStorage.setItem(COLLAPSE_KEY, collapsed ? '1' : '0');
        }
    };

    const openMobileSidebar = () => {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
    };

    const closeMobileSidebar = () => {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
    };

    if (localStorage.getItem(COLLAPSE_KEY) === '1') {
        setSidebarCollapsed(true);
    }

    mobileToggle?.addEventListener('click', () => {
        sidebar?.classList.contains('-translate-x-full') ? openMobileSidebar() : closeMobileSidebar();
    });

    overlay?.addEventListener('click', closeMobileSidebar);

    collapseBtn?.addEventListener('click', () => setSidebarCollapsed(true));
    expandBtn?.addEventListener('click', () => setSidebarCollapsed(false));

    window.addEventListener('resize', () => {
        if (isDesktop()) {
            closeMobileSidebar();
        }
    });

    const closeUserMenu = () => {
        userMenuDropdown?.classList.add('hidden');
        userMenuButton?.setAttribute('aria-expanded', 'false');
    };

    const openUserMenu = () => {
        userMenuDropdown?.classList.remove('hidden');
        userMenuButton?.setAttribute('aria-expanded', 'true');
    };

    userMenuButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = !userMenuDropdown?.classList.contains('hidden');
        isOpen ? closeUserMenu() : openUserMenu();
    });

    document.addEventListener('click', (event) => {
        if (!userMenu?.contains(event.target)) {
            closeUserMenu();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeUserMenu();
            if (!isDesktop()) {
                closeMobileSidebar();
            }
        }
    });

    const websiteNavToggle = document.getElementById('website-nav-toggle');
    const websiteNavMobile = document.getElementById('website-nav-mobile');

    websiteNavToggle?.addEventListener('click', () => {
        const isOpen = !websiteNavMobile?.classList.contains('hidden');
        websiteNavMobile?.classList.toggle('hidden', isOpen);
        websiteNavToggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    });

    const isFinePointer = () => window.matchMedia('(hover: hover) and (pointer: fine)').matches;

    document.querySelectorAll('[data-nav-dropdown]').forEach((dropdown) => {
        const trigger = dropdown.querySelector('.website-nav-dropdown__trigger');
        const menu = dropdown.querySelector('.website-nav-dropdown__menu');
        let closeTimer = null;

        const openDropdown = () => {
            clearTimeout(closeTimer);
            document.querySelectorAll('[data-nav-dropdown].is-open').forEach((openDropdown) => {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('is-open');
                    openDropdown.querySelector('.website-nav-dropdown__trigger')?.setAttribute('aria-expanded', 'false');
                }
            });
            dropdown.classList.add('is-open');
            trigger?.setAttribute('aria-expanded', 'true');
        };

        const closeDropdown = () => {
            dropdown.classList.remove('is-open');
            trigger?.setAttribute('aria-expanded', 'false');
        };

        const scheduleClose = () => {
            clearTimeout(closeTimer);
            closeTimer = setTimeout(closeDropdown, 200);
        };

        if (isFinePointer()) {
            dropdown.addEventListener('mouseenter', openDropdown);
            dropdown.addEventListener('mouseleave', scheduleClose);
        }

        trigger?.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            const isOpen = dropdown.classList.contains('is-open');
            isOpen ? closeDropdown() : openDropdown();
        });

        menu?.addEventListener('click', (event) => {
            event.stopPropagation();
        });
    });

    document.addEventListener('click', (event) => {
        document.querySelectorAll('[data-nav-dropdown].is-open').forEach((dropdown) => {
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('is-open');
                dropdown.querySelector('.website-nav-dropdown__trigger')?.setAttribute('aria-expanded', 'false');
            }
        });
    });

    document.querySelectorAll('[data-sidebar-dropdown]').forEach((group) => {
        const trigger = group.querySelector('.website-page-sidebar__group-trigger');

        trigger?.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            const isOpen = group.classList.contains('is-open');

            group.classList.toggle('is-open', !isOpen);
            trigger.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
        });
    });

    document.querySelectorAll('[data-mobile-nav-group]').forEach((group) => {
        const trigger = group.querySelector('.website-mobile-nav-group__trigger');
        const panel = group.querySelector('.website-mobile-nav-group__panel');

        trigger?.addEventListener('click', () => {
            const isOpen = group.classList.contains('is-open');
            group.classList.toggle('is-open', !isOpen);
            panel?.classList.toggle('hidden', isOpen);
        });
    });

    const heroSlider = document.getElementById('hero-slider');
    if (heroSlider) {
        const slides = [...heroSlider.querySelectorAll('.hero-slide')];
        const dots = [...heroSlider.querySelectorAll('.hero-slider__dot')];
        let current = 0;
        let timer = null;
        const interval = 6000;

        const goToSlide = (index) => {
            if (!slides.length) return;

            current = (index + slides.length) % slides.length;

            slides.forEach((slide, i) => {
                slide.classList.toggle('is-active', i === current);
                const image = slide.querySelector('.hero-slide__image');
                if (image) {
                    image.style.animation = 'none';
                    void image.offsetWidth;
                    if (i === current) {
                        image.style.animation = '';
                    }
                }
            });

            dots.forEach((dot, i) => dot.classList.toggle('is-active', i === current));
        };

        const startAutoplay = () => {
            clearInterval(timer);
            timer = setInterval(() => goToSlide(current + 1), interval);
        };

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                goToSlide(Number(dot.dataset.slideTo));
                startAutoplay();
            });
        });

        heroSlider.addEventListener('mouseenter', () => clearInterval(timer));
        heroSlider.addEventListener('mouseleave', startAutoplay);

        goToSlide(0);
        startAutoplay();
    }

    const eventsSlider = document.getElementById('events-slider');
    if (eventsSlider) {
        const eventSlides = [...eventsSlider.querySelectorAll('[data-event-slide]')];
        const eventDots = [...eventsSlider.querySelectorAll('[data-event-dot]')];
        let eventCurrent = 0;
        let eventTimer = null;
        const eventInterval = 7000;

        const goToEvent = (index) => {
            if (!eventSlides.length) return;

            eventCurrent = (index + eventSlides.length) % eventSlides.length;

            eventSlides.forEach((slide, i) => slide.classList.toggle('is-active', i === eventCurrent));
            eventDots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === eventCurrent);
                dot.setAttribute('aria-selected', i === eventCurrent ? 'true' : 'false');
            });
        };

        const startEventAutoplay = () => {
            clearInterval(eventTimer);
            if (eventSlides.length > 1) {
                eventTimer = setInterval(() => goToEvent(eventCurrent + 1), eventInterval);
            }
        };

        eventDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                goToEvent(Number(dot.dataset.eventDot));
                startEventAutoplay();
            });
        });

        eventsSlider.addEventListener('mouseenter', () => clearInterval(eventTimer));
        eventsSlider.addEventListener('mouseleave', startEventAutoplay);

        goToEvent(0);
        startEventAutoplay();
    }
});
