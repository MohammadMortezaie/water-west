(() => {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');
    const servicesToggle = document.querySelector('[data-services-toggle]');
    const dropdown = servicesToggle?.closest('.nav-dropdown');
    const navToggleLabel = navToggle?.querySelector('.sr-only');

    const setNavState = (open) => {
        navToggle?.setAttribute('aria-expanded', String(open));
        nav?.classList.toggle('is-open', open);
        document.body.classList.toggle('nav-open', open);
        if (navToggleLabel) navToggleLabel.textContent = open ? 'Close menu' : 'Open menu';
    };

    const closeNav = () => setNavState(false);

    navToggle?.addEventListener('click', () => {
        const opening = navToggle.getAttribute('aria-expanded') !== 'true';
        setNavState(opening);
        if (opening) nav?.querySelector('a')?.focus();
    });

    servicesToggle?.addEventListener('click', () => {
        const opening = servicesToggle.getAttribute('aria-expanded') !== 'true';
        servicesToggle.setAttribute('aria-expanded', String(opening));
        dropdown?.classList.toggle('is-open', opening);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeNav();
            servicesToggle?.setAttribute('aria-expanded', 'false');
            dropdown?.classList.remove('is-open');
            navToggle?.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (dropdown && !dropdown.contains(event.target)) {
            servicesToggle?.setAttribute('aria-expanded', 'false');
            dropdown.classList.remove('is-open');
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1020) closeNav();
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeNav));
})();
