/**
 * ARK Travelers – Single package: scroll reveal, sticky booking CTA, accordion
 * Modern, elegant behavior; respects prefers-reduced-motion.
 */

(function () {
    'use strict';

    var main = document.querySelector('.ark-main-single-umrah');
    if (!main) return;

    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ----- Scroll reveal: sections get .is-visible when in view ----- */
    var revealEls = main.querySelectorAll('[data-reveal]');
    if (revealEls.length && !reducedMotion) {
        var revealOpts = { rootMargin: '0px 0px -8% 0px', threshold: 0 };
        var revealObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, revealOpts);
        revealEls.forEach(function (el) {
            revealObs.observe(el);
        });
    } else if (revealEls.length && reducedMotion) {
        revealEls.forEach(function (el) {
            el.classList.add('is-visible');
        });
    }

    /* Accordion is handled by main.js (single listener) to avoid double-toggle. */

    /* ----- Sticky CTA: show when hero booking card scrolls out of view ----- */
    var stickyCta = document.getElementById('ark-sticky-cta');
    var heroCard = document.querySelector('.ark-hero-booking-card');
    if (stickyCta && heroCard) {
        var stickyOpts = { rootMargin: '-80px 0px 0px 0px', threshold: 0 };
        var stickyObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    stickyCta.classList.remove('is-visible');
                    stickyCta.setAttribute('aria-hidden', 'true');
                } else {
                    stickyCta.classList.add('is-visible');
                    stickyCta.setAttribute('aria-hidden', 'false');
                }
            });
        }, stickyOpts);
        stickyObs.observe(heroCard);
    }
})();
