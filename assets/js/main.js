/**
 * ARK Travelers – Navigation, header scroll, mobile menu, scroll reveal (vanilla JS)
 */

(function () {
    'use strict';

    var HEADER_SCROLL_THRESHOLD = 100;

    /**
     * Toggle scrolled class on header after threshold
     */
    function initHeaderScroll() {
        var header = document.getElementById('masthead');
        if (!header) return;

        function updateHeader() {
            // Treat both the original .ark-hero and the new airplane-scroll
            // banner as "hero" sections. On pages with a hero, keep the header
            // transparent at the top and switch to white after scrolling down
            // a bit. On other pages, keep the solid white header.
            var heroElement = document.querySelector('.ark-hero, #airplane-scroll, #hero-video-scrub, .ark-fly-hero-banner');
            var hasHero = !!heroElement;
            var overWhite = !hasHero || window.pageYOffset > HEADER_SCROLL_THRESHOLD;
            if (overWhite) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }

        window.addEventListener('scroll', throttle(updateHeader, 50), { passive: true });
        updateHeader(); // run once so non-hero pages get .scrolled immediately
    }

    /**
     * Mobile menu toggle – body scroll lock and scroll position restore
     */
    function initMobileMenu() {
        var toggle = document.querySelector('.ark-nav-toggle');
        var nav = document.getElementById('ark-primary-nav');
        if (!toggle || !nav) return;
        var scrollY = 0;

        function openMenu() {
            scrollY = window.pageYOffset || document.documentElement.scrollTop;
            toggle.setAttribute('aria-expanded', 'true');
            nav.classList.add('is-open');
            document.body.classList.add('ark-nav-open');
            document.body.style.top = '-' + scrollY + 'px';
        }

        function closeMenu() {
            toggle.setAttribute('aria-expanded', 'false');
            nav.classList.remove('is-open');
            document.body.classList.remove('ark-nav-open');
            document.body.style.top = '';
            if (scrollY) {
                requestAnimationFrame(function () {
                    window.scrollTo(0, scrollY);
                });
            }
        }

        toggle.addEventListener('click', function () {
            var expanded = toggle.getAttribute('aria-expanded') === 'true';
            if (expanded) closeMenu();
            else openMenu();
        });

        document.addEventListener('click', function (e) {
            if (nav.classList.contains('is-open') && !nav.contains(e.target) && !toggle.contains(e.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('is-open')) {
                closeMenu();
            }
        });
    }

    /**
     * Scroll reveal – add .ark-reveal-visible when in viewport
     */
    function initScrollReveal() {
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var elements = document.querySelectorAll('.ark-reveal, .ark-reveal-stagger');
        if (!elements.length) return;

        if (reduced) {
            elements.forEach(function (el) { el.classList.add('ark-reveal-visible'); });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('ark-reveal-visible');
                    }
                });
            },
            { rootMargin: '0px 0px -60px 0px', threshold: 0.1 }
        );
        elements.forEach(function (el) { observer.observe(el); });
    }

    /**
     * Accordion: toggle .ark-accordion-body on .ark-accordion-head click
     */
    function initAccordion() {
        document.addEventListener('click', function (e) {
            var head = e.target.closest('.ark-accordion-head');
            if (!head) return;
            var item = head.closest('.ark-accordion-item');
            if (!item) return;
            e.preventDefault();
            e.stopPropagation();
            
            var isOpen = item.classList.contains('is-open');
            var container = item.closest('.ark-accordion');
            
            // Close all items in the same container
            if (container) {
                container.querySelectorAll('.ark-accordion-item').forEach(function (el) {
                    if (el !== item) {
                        el.classList.remove('is-open');
                        var btn = el.querySelector('.ark-accordion-head');
                        if (btn) btn.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            
            // Toggle the clicked item
            if (isOpen) {
                item.classList.remove('is-open');
                head.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('is-open');
                head.setAttribute('aria-expanded', 'true');
            }
        });
    }

    /**
     * Contact form AJAX submit
     */
    function initContactForm() {
        var form = document.getElementById('ark-contact-form');
        if (!form || typeof arkData === 'undefined') return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var msgBox = document.getElementById('ark-contact-message-box');
            var submitBtn = form.querySelector('button[type="submit"]');
            var data = new FormData(form);
            data.append('action', 'ark_contact_form');
            data.append('nonce', arkData.nonce);
            data.append('name', form.querySelector('#ark-contact-name').value);
            data.append('email', form.querySelector('#ark-contact-email').value);
            data.append('phone', form.querySelector('#ark-contact-phone').value);
            data.append('subject', form.querySelector('#ark-contact-subject').value);
            data.append('message', form.querySelector('#ark-contact-message').value);
            data.append('privacy', form.querySelector('input[name="privacy"]').checked ? '1' : '0');

            if (submitBtn) submitBtn.disabled = true;
            if (msgBox) {
                msgBox.className = 'ark-form-message';
                msgBox.textContent = '';
            }

            fetch(arkData.ajaxUrl, {
                method: 'POST',
                body: data,
                credentials: 'same-origin',
            })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (msgBox) {
                        msgBox.className = 'ark-form-message ' + (res.success ? 'success' : 'error');
                        msgBox.textContent = res.data && res.data.message ? res.data.message : (res.success ? 'Thank you.' : 'Something went wrong.');
                    }
                    if (res.success) form.reset();
                    if (submitBtn) submitBtn.disabled = false;
                })
                .catch(function () {
                    if (msgBox) {
                        msgBox.className = 'ark-form-message error';
                        msgBox.textContent = 'Network error. Please try again.';
                    }
                    if (submitBtn) submitBtn.disabled = false;
                });
        });
    }

    /**
     * Cart link: use WooCommerce cart URL if available, else #
     */
    function initCart() {
        var cartLink = document.querySelector('.ark-header-cart');
        if (!cartLink) return;
        if (typeof arkData !== 'undefined' && arkData.homeUrl) {
            if (typeof wc_add_to_cart_params === 'undefined') {
                cartLink.href = arkData.homeUrl + '#';
            }
        }
        var countEl = document.querySelector('.ark-cart-count');
        if (countEl && typeof wc_cart_fragments_params === 'undefined') {
            countEl.style.display = 'none';
        }
    }

    /**
     * Language dropdown: redirect to selected language URL
     */
    function initLangSelect() {
        var select = document.getElementById('ark-lang-select');
        if (!select) return;
        select.addEventListener('change', function () {
            var url = this.value;
            if (url) window.location.href = url;
        });
    }

    function throttle(fn, delay) {
        var last = 0;
        return function () {
            var now = Date.now();
            if (now - last >= delay) {
                last = now;
                fn.apply(this, arguments);
            }
        };
    }

    function init() {
        initHeaderScroll();
        initMobileMenu();
        initScrollReveal();
        initAccordion();
        initContactForm();
        initCart();
        initLangSelect();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
