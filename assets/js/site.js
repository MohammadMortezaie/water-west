(() => {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');
    const servicesToggle = document.querySelector('[data-services-toggle]');
    const dropdown = servicesToggle?.closest('.nav-dropdown');

    const closeNav = () => {
        navToggle?.setAttribute('aria-expanded', 'false');
        nav?.classList.remove('is-open');
        document.body.classList.remove('nav-open');
    };

    navToggle?.addEventListener('click', () => {
        const opening = navToggle.getAttribute('aria-expanded') !== 'true';
        navToggle.setAttribute('aria-expanded', String(opening));
        nav?.classList.toggle('is-open', opening);
        document.body.classList.toggle('nav-open', opening);
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
        }
    });

    nav?.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeNav));
})();
