/**
 * ARK Travelers – Routes Explorer (Boarding-pass carousel)
 * Smooth auto-cycling destination showcase with manual controls.
 *
 * Behaviour:
 *  – Auto-advances every CYCLE_MS while in view & document is visible.
 *  – Click prev/next or any dot to navigate manually.
 *  – Arrow keys (←/→) move when the stage has focus.
 *  – Pauses on hover, focus, tab hidden, or out-of-view.
 *  – Resets the auto-cycle timer after any manual interaction.
 */
(function () {
    'use strict';

    const CYCLE_MS = 5500;

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        const stage = document.querySelector('[data-routes-stage]');
        if (!stage) return;

        const deck   = stage.querySelector('[data-routes-deck]');
        const passes = deck ? Array.from(deck.querySelectorAll('.ark-route-pass')) : [];
        const dots   = Array.from(document.querySelectorAll('[data-routes-dot]'));
        const prev   = document.querySelector('[data-routes-prev]');
        const next   = document.querySelector('[data-routes-next]');
        const glow   = document.querySelector('.ark-routes-glow');

        if (passes.length < 2) return;

        let current = passes.findIndex((p) => p.classList.contains('is-active'));
        if (current < 0) current = 0;
        let timerId = null;
        let inView  = false;
        let hovered = false;

        function applyGlow(index) {
            if (!glow) return;
            const accent = passes[index].style.getPropertyValue('--route-accent') || '#4A90A4';
            glow.style.setProperty('--route-accent', accent.trim());
        }

        function goTo(index) {
            const len = passes.length;
            const target = ((index % len) + len) % len;
            if (target === current) return;

            const outgoing = passes[current];
            const incoming = passes[target];

            outgoing.classList.remove('is-active');
            outgoing.classList.add('is-leaving');
            outgoing.setAttribute('aria-hidden', 'true');

            // Force reflow so the animation restarts when revisiting a slide
            void incoming.offsetWidth;

            incoming.classList.remove('is-leaving');
            incoming.classList.add('is-active');
            incoming.setAttribute('aria-hidden', 'false');

            dots.forEach((dot, i) => {
                const active = i === target;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            current = target;
            applyGlow(target);

            // Tidy up the leaving class after the transition completes
            window.setTimeout(() => outgoing.classList.remove('is-leaving'), 800);
        }

        function nextSlide() { goTo(current + 1); }
        function prevSlide() { goTo(current - 1); }

        function startAuto() {
            stopAuto();
            if (!inView || hovered || document.hidden) return;
            timerId = window.setInterval(nextSlide, CYCLE_MS);
        }

        function stopAuto() {
            if (timerId !== null) {
                window.clearInterval(timerId);
                timerId = null;
            }
        }

        function userJumped(index) {
            goTo(index);
            startAuto(); // reset cadence so next swap isn't too soon
        }

        // ─── Controls ─────────────────────────────────────────────────
        if (next) next.addEventListener('click', () => { nextSlide(); startAuto(); });
        if (prev) prev.addEventListener('click', () => { prevSlide(); startAuto(); });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => userJumped(i));
        });

        stage.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') { nextSlide(); startAuto(); }
            if (e.key === 'ArrowLeft')  { prevSlide(); startAuto(); }
        });

        // Pause on hover / focus
        stage.addEventListener('mouseenter', () => { hovered = true;  stopAuto(); });
        stage.addEventListener('mouseleave', () => { hovered = false; startAuto(); });
        stage.addEventListener('focusin',  () => { hovered = true;  stopAuto(); });
        stage.addEventListener('focusout', () => { hovered = false; startAuto(); });

        // Pause when tab hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopAuto();
            else startAuto();
        });

        // Touch swipe
        let touchStartX = 0;
        let touchStartY = 0;
        deck.addEventListener('touchstart', (e) => {
            if (!e.touches[0]) return;
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        deck.addEventListener('touchend', (e) => {
            if (!e.changedTouches[0]) return;
            const dx = e.changedTouches[0].clientX - touchStartX;
            const dy = e.changedTouches[0].clientY - touchStartY;
            if (Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy)) return; // ignore short / vertical swipes
            if (dx < 0) nextSlide(); else prevSlide();
            startAuto();
        }, { passive: true });

        // Only autoplay when visible on screen
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    inView = entry.isIntersecting;
                    if (inView) startAuto(); else stopAuto();
                });
            }, { threshold: 0.25 });
            io.observe(stage);
        } else {
            inView = true;
            startAuto();
        }

        applyGlow(current);
    }
})();
