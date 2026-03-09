/**
 * ARK Travelers – GSAP panel parallax: pin full-viewport panels (no snap)
 * Each .panel pins when its top hits the viewport; user can scroll freely.
 */
(function () {
    'use strict';

    function init() {
        var gsap = window.gsap;
        var ScrollTrigger = window.ScrollTrigger;
        if (!gsap || !ScrollTrigger) return;

        var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reducedMotion) return;

        gsap.registerPlugin(ScrollTrigger);

        var panels = gsap.utils.toArray('.panel');
        if (!panels.length) return;

        // Pin each panel when it reaches the top; shorter-than-viewport panels pin at top.
        // The airplane-scroll hero remains pinned (custom scroll logic),
        // but the video panel scrolls normally so there is no long \"dead\" scroll
        // zone and the transition into the next section stays smooth.
        panels.forEach(function (panel) {
            var isVideoPanel = panel.classList.contains('ark-video-section');
            ScrollTrigger.create({
                trigger: panel,
                start: function () {
                    return panel.offsetHeight < window.innerHeight ? 'top top' : 'bottom bottom';
                },
                pin: !isVideoPanel,
                pinSpacing: false,
            });
        });

        // Video panel: ensure GIF and overlay are visible (no slide-up in panel mode)
        var videoSection = document.getElementById('ark-video-section');
        if (videoSection) {
            var wrapper = videoSection.querySelector('.ark-video-wrapper');
            var gif = videoSection.querySelector('.ark-video-parallax');
            if (wrapper) {
                gsap.set(wrapper, { yPercent: 0 });
                wrapper.classList.add('is-visible');
            }
            if (gif) {
                gsap.set(gif, { opacity: 1 });
                gif.classList.add('is-visible');
            }
        }

        ScrollTrigger.refresh();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
