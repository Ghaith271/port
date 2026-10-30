(() => {
    'use strict';
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const navToggle = document.querySelector('.nav-toggle');
    const navigation = document.querySelector('.primary-nav');
    const closeNavigation = () => {
        navToggle?.setAttribute('aria-expanded', 'false');
        navToggle?.setAttribute('aria-label', 'فتح القائمة');
        navigation?.classList.remove('is-open');
    };
    navToggle?.addEventListener('click', () => {
        const open = navToggle.getAttribute('aria-expanded') !== 'true';
        navToggle.setAttribute('aria-expanded', String(open));
        navToggle.setAttribute('aria-label', open ? 'إغلاق القائمة' : 'فتح القائمة');
        navigation?.classList.toggle('is-open', open);
    });
    navigation?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeNavigation));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            const wasOpen = navToggle?.getAttribute('aria-expanded') === 'true';
            closeNavigation();
            if (wasOpen) navToggle.focus();
        }
    });
    document.addEventListener('click', event => {
        if (!event.target.closest('.nav-shell')) closeNavigation();
    });

    if ('IntersectionObserver' in window && !reducedMotion.matches) {
        const revealObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });
        document.querySelectorAll('.reveal').forEach((element, index) => {
            element.style.setProperty('--reveal-delay', `${Math.min(index % 3, 2) * 70}ms`);
            element.classList.add('reveal-ready');
            revealObserver.observe(element);
        });
    }

    const filters = document.querySelectorAll('[data-filter]');
    const projects = document.querySelectorAll('.project-card');
    const status = document.querySelector('#filter-status');
    filters.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.filter;
            let visible = 0;
            filters.forEach(filter => {
                const selected = filter === button;
                filter.classList.toggle('active', selected);
                filter.setAttribute('aria-pressed', String(selected));
            });
            projects.forEach(project => {
                const matches = category === 'all' || project.dataset.category === category;
                project.hidden = !matches;
                if (matches) {
                    visible++;
                    project.classList.add('is-visible');
                }
            });
            if (status) status.textContent = `عدد المشاريع المعروضة: ${visible}`;
        });
    });

    if ('IntersectionObserver' in window) {
        const sectionObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                navigation?.querySelectorAll('.nav-item').forEach(link => {
                    const active = link.getAttribute('href') === `#${entry.target.id}`;
                    link.classList.toggle('active', active);
                    if (active) link.setAttribute('aria-current', 'location');
                    else link.removeAttribute('aria-current');
                });
            });
        }, { rootMargin: '-15% 0px -65% 0px' });
        document.querySelectorAll('main section[id]').forEach(section => sectionObserver.observe(section));
    }

    const heroArt = document.querySelector('.hero-art');
    if (heroArt && window.matchMedia('(hover: hover)').matches && !reducedMotion.matches) {
        heroArt.addEventListener('pointermove', event => {
            const bounds = heroArt.getBoundingClientRect();
            const x = (event.clientX - bounds.left) / bounds.width - 0.5;
            const y = (event.clientY - bounds.top) / bounds.height - 0.5;
            heroArt.style.setProperty('--tilt-x', `${-y * 6}deg`);
            heroArt.style.setProperty('--tilt-y', `${x * 6}deg`);
        });
        heroArt.addEventListener('pointerleave', () => {
            heroArt.style.setProperty('--tilt-x', '0deg');
            heroArt.style.setProperty('--tilt-y', '0deg');
        });
    }

    const formFeedback = document.querySelector('.form-feedback');
    if (formFeedback) {
        formFeedback.scrollIntoView({ behavior: reducedMotion.matches ? 'instant' : 'smooth', block: 'center' });
    }
})();
