document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof ScrollToPlugin === 'undefined') {
        console.warn('NexlifyScroll: GSAP, ScrollTrigger, or ScrollToPlugin is missing.');
        return;
    }

    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    const settings = window.nexlifyscrollSettings || {};
    if (settings.scrollTop && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const button = document.querySelector('#nexlifyscroll-top');
        if (button) {
            button.addEventListener('click', () => {
                gsap.to(window, {
                    scrollTo: { y: 0 },
                    duration: settings.duration,
                    ease: settings.easing
                });
            });
            gsap.to(button, {
                opacity: 1,
                duration: 0.3,
                scrollTrigger: {
                    trigger: document.body,
                    start: 'top -200',
                    end: 'top -100',
                    toggleActions: 'play none none reverse',
                }
            });
        }
    }
});