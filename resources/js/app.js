import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Animations au scroll avec Intersection Observer
document.addEventListener('DOMContentLoaded', function() {
    // Observer pour les animations d'apparition au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in-view');
                // Ne plus observer cet élément après l'animation
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observer tous les éléments avec la classe 'scroll-animate'
    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });

    // Animation progressive pour les cartes de services
    const serviceCards = document.querySelectorAll('.service-card');
    serviceCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    // Smooth scroll pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
});

Alpine.start();
