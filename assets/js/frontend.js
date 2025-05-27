document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined' || typeof ScrollToPlugin === 'undefined') {
        console.warn('NexlifyScroll: GSAP, ScrollTrigger, or ScrollToPlugin is missing.');
        return;
    }

    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    const settings = window.nexlifyscrollSettings || {};
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        // Scroll-to-top functionality
        if (settings.scrollTop) {
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

                if (settings.progressBarEnabled) {
                    const icon = button.querySelector('.nexlifyscroll-icon');
                    if (icon) {
                        gsap.to(icon, {
                            '--progress-angle': 100,
                            scrollTrigger: {
                                trigger: document.body,
                                start: 'top top',
                                end: 'bottom bottom',
                                scrub: 0.1,
                                onUpdate: self => {
                                    const progress = self.progress * 100;
                                    icon.style.setProperty('--progress-angle', progress);
                                }
                            }
                        });
                    }
                }
            }
        }

        // Smooth scroll and anchor link functionality
        if (settings.smoothScrollEnabled || settings.anchorScrollEnabled) {
            let currentScroll = window.scrollY;
            let targetScroll = currentScroll;
            let velocity = 0;
            let isAnimating = false;

            // Normalize wheel delta across browsers
            const normalizeWheel = (event) => {
                let delta = 0;
                if (event.deltaY) {
                    delta = event.deltaY;
                } else if (event.detail) {
                    delta = event.detail * -40;
                }
                return delta * settings.smoothScrollWheelMultiplier;
            };

            // Handle wheel events
            window.addEventListener('wheel', (event) => {
                event.preventDefault();
                if (!isAnimating) {
                    const delta = normalizeWheel(event);
                    targetScroll += delta;
                    targetScroll = Math.max(0, Math.min(targetScroll, document.body.scrollHeight - window.innerHeight));
                }
            }, { passive: false });

            // Handle keyboard events (Page Up/Down, Arrow Up/Down)
            window.addEventListener('keydown', (event) => {
                if (['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown'].includes(event.key)) {
                    event.preventDefault();
                    if (!isAnimating) {
                        const step = 300 * settings.smoothScrollWheelMultiplier;
                        if (event.key === 'ArrowUp' || event.key === 'PageUp') {
                            targetScroll -= step;
                        } else if (event.key === 'ArrowDown' || event.key === 'PageDown') {
                            targetScroll += step;
                        }
                        targetScroll = Math.max(0, Math.min(targetScroll, document.body.scrollHeight - window.innerHeight));
                    }
                }
            });

            // Smooth scroll update loop using GSAP ticker
            if (settings.smoothScrollEnabled) {
                gsap.ticker.add(() => {
                    if (!isAnimating) {
                        currentScroll += (targetScroll - currentScroll) * settings.smoothScrollLerp;
                        velocity = (targetScroll - currentScroll) * settings.smoothScrollLerp;
                        window.scrollTo(0, Math.round(currentScroll));
                    }
                    ScrollTrigger.update();
                });
            } else {
                gsap.ticker.add(() => {
                    if (!isAnimating) {
                        window.scrollTo(0, Math.round(targetScroll));
                    }
                    ScrollTrigger.update();
                });
            }

            // Anchor Link Smooth Scrolling
            if (settings.anchorScrollEnabled) {
                try {
                    const links = document.querySelectorAll(settings.linkSelectors);
                    links.forEach(link => {
                        link.addEventListener('click', (e) => {
                            const hash = link.getAttribute('href').split('#')[1];
                            const target = document.getElementById(hash);
                            if (target && !e.defaultPrevented) {
                                e.preventDefault();
                                isAnimating = true;
                                let dynamicOffset = 0;
                                if (settings.dynamicOffsetSelector) {
                                    const offsetElement = document.querySelector(settings.dynamicOffsetSelector);
                                    dynamicOffset = offsetElement ? offsetElement.getBoundingClientRect().height : 0;
                                    console.log('Dynamic Offset Height:', dynamicOffset);
                                }
                                const effectiveOffset = dynamicOffset > 0 ? dynamicOffset : (settings.scrollOffset || 0);
                                console.log('Effective Offset:', effectiveOffset); // Debug log
                                const targetPosition = target.getBoundingClientRect().top + window.scrollY - effectiveOffset;
                                console.log('Target Position:', targetPosition); // Debug log
                                gsap.to(window, {
                                    scrollTo: { y: targetPosition, autoKill: true },
                                    duration: settings.animationDuration,
                                    ease: settings.easingFunction,
                                    onComplete: () => {
                                        isAnimating = false;
                                        targetScroll = targetPosition;
                                        currentScroll = targetPosition;
                                        window.location.hash = hash;
                                        target.focus({ preventScroll: true });
                                    }
                                });
                            }
                        });
                    });
                } catch (error) {
                    console.warn('NexlifyScroll: Invalid link selector for anchor links.', error);
                }
            }

            // Sync with GSAP ScrollTrigger
            ScrollTrigger.scrollerProxy(document.body, {
                scrollTop(value) {
                    if (arguments.length) {
                        targetScroll = value;
                        currentScroll = value;
                        window.scrollTo(0, value);
                    }
                    return currentScroll;
                },
                getBoundingClientRect() {
                    return { top: 0, left: 0, width: window.innerWidth, height: window.innerHeight };
                }
            });

            window.addEventListener('scroll', () => {
                ScrollTrigger.update();
            });
        }
    }
});