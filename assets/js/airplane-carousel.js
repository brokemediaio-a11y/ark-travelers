/**
 * ARK Travelers – High-end airplane service carousel (GSAP)
 * Plane + card fly in from left, hover with bobbing, exit right on next/prev, re-enter with new card.
 * Features: GSAP timelines, Intersection Observer, touch swipe with velocity, keyboard, reduced motion.
 */
(function () {
    'use strict';

    var reducedMotion = typeof window.matchMedia !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var duration = reducedMotion ? 0.2 : 2;
    var exitDuration = reducedMotion ? 0.15 : 1.4;
    var hoverDuration = reducedMotion ? 0 : 1.8;

    function getStage() {
        return document.querySelector('#ark-services .ark-airplane-stage');
    }

    function getElements() {
        var stage = getStage();
        if (!stage) return null;
        return {
            stage: stage,
            plane: stage.querySelector('.ark-airplane'),
            rope: stage.querySelector('.ark-airplane-rope'),
            cards: stage.querySelectorAll('.ark-service-card'),
            prev: document.querySelector('#ark-services .ark-airplane-prev'),
            next: document.querySelector('#ark-services .ark-airplane-next'),
            dots: document.querySelectorAll('#ark-services .ark-airplane-dot')
        };
    }

    function ArkAirplaneCarousel() {
        this.el = getElements();
        if (!this.el || !this.el.plane || !this.el.cards.length) return;
        this.current = 0;
        this.total = this.el.cards.length;
        this.entryPlayed = false;
        this.busy = false;
        this.touchStartX = 0;
        this.touchStartT = 0;
        this.touchEndX = 0;
        this.touchEndT = 0;
        this.tlHover = null;
        this.tlRope = null;
        this.init();
    }

    ArkAirplaneCarousel.prototype.init = function () {
        var self = this;
        var gsap = window.gsap;
        if (!gsap) return;

        gsap.set(self.el.plane, { xPercent: -200, yPercent: -50, opacity: 0 });
        gsap.set(self.el.rope, { opacity: 0 });
        self.el.cards.forEach(function (card, i) {
            gsap.set(card, { xPercent: -50, yPercent: -50, opacity: i === 0 ? 0 : 0 });
        });

        self.observeEntry(gsap);
        if (self.el.prev) self.el.prev.addEventListener('click', function () { self.prev(gsap); });
        if (self.el.next) self.el.next.addEventListener('click', function () { self.next(gsap); });
        self.el.dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { self.go(gsap, i); });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowLeft') self.prev(gsap);
            if (e.key === 'ArrowRight') self.next(gsap);
        });
        self.touchSetup(gsap);
    };

    ArkAirplaneCarousel.prototype.observeEntry = function (gsap) {
        var self = this;
        var section = document.getElementById('ark-services');
        if (!section || !window.IntersectionObserver) {
            self.playEntry(gsap);
            return;
        }
        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || self.entryPlayed) return;
                    self.entryPlayed = true;
                    self.playEntry(gsap);
                });
            },
            { threshold: 0.2, rootMargin: '0px 0px -50px 0px' }
        );
        observer.observe(section);
    };

    ArkAirplaneCarousel.prototype.playEntry = function (gsap) {
        var self = this;
        var plane = self.el.plane;
        var rope = self.el.rope;
        var card = self.el.cards[self.current];

        var tl = gsap.timeline({ onComplete: function () { self.startHover(gsap); } });
        tl.to(plane, {
            xPercent: 0,
            yPercent: -50,
            opacity: 1,
            duration: duration,
            ease: 'power3.out',
            overwrite: true
        }, 0);
        tl.to(card, {
            xPercent: -50,
            yPercent: -50,
            opacity: 1,
            duration: duration,
            ease: 'power3.out',
            overwrite: true
        }, 0);
        tl.to(rope, { opacity: 1, duration: duration * 0.5, ease: 'power2.out' }, duration * 0.3);
    };

    ArkAirplaneCarousel.prototype.startHover = function (gsap) {
        var self = this;
        var plane = self.el.plane;
        var rope = self.el.rope;
        if (hoverDuration <= 0) return;
        if (self.tlHover) self.tlHover.kill();
        if (self.tlRope) self.tlRope.kill();
        self.tlHover = gsap.to(plane, {
            y: '-=14',
            rotation: 3,
            duration: hoverDuration,
            yoyo: true,
            repeat: -1,
            ease: 'sine.inOut'
        });
        self.tlRope = gsap.to(rope, {
            rotation: 2,
            duration: hoverDuration * 0.9,
            yoyo: true,
            repeat: -1,
            ease: 'sine.inOut'
        });
    };

    ArkAirplaneCarousel.prototype.stopHover = function () {
        if (this.tlHover) this.tlHover.kill();
        if (this.tlRope) this.tlRope.kill();
        this.tlHover = null;
        this.tlRope = null;
        window.gsap.set(this.el.plane, { y: 0, rotation: 0 });
        window.gsap.set(this.el.rope, { rotation: 0 });
    };

    ArkAirplaneCarousel.prototype.next = function (gsap) {
        var next = (this.current + 1) % this.total;
        this.transition(gsap, next);
    };

    ArkAirplaneCarousel.prototype.prev = function (gsap) {
        var prev = (this.current - 1 + this.total) % this.total;
        this.transition(gsap, prev);
    };

    ArkAirplaneCarousel.prototype.go = function (gsap, index) {
        if (index === this.current || index < 0 || index >= this.total) return;
        this.transition(gsap, index);
    };

    ArkAirplaneCarousel.prototype.transition = function (gsap, nextIndex) {
        var self = this;
        if (self.busy) return;
        self.busy = true;
        self.stopHover();

        var plane = self.el.plane;
        var rope = self.el.rope;
        var currentCard = self.el.cards[self.current];
        var nextCard = self.el.cards[nextIndex];

        var tl = gsap.timeline({
            onComplete: function () {
                currentCard.classList.remove('active');
                nextCard.classList.add('active');
                self.updateDots(nextIndex);
                self.current = nextIndex;
                gsap.set(plane, { xPercent: -200, yPercent: -50, opacity: 0, y: 0 });
                gsap.set(rope, { opacity: 0, rotation: 0 });
                gsap.set(nextCard, { xPercent: -200, yPercent: -50, opacity: 0 });

                var tlIn = gsap.timeline({
                    onComplete: function () {
                        self.busy = false;
                        self.startHover(gsap);
                    }
                });
                tlIn.to(plane, {
                    xPercent: 0,
                    yPercent: -50,
                    opacity: 1,
                    duration: duration,
                    ease: 'power3.out'
                }, 0);
                tlIn.to(nextCard, {
                    xPercent: -50,
                    yPercent: -50,
                    opacity: 1,
                    duration: duration,
                    ease: 'power3.out'
                }, 0);
                tlIn.to(rope, { opacity: 1, duration: duration * 0.5 }, duration * 0.3);
            }
        });

        tl.to(currentCard, { opacity: 0, duration: 0.25, ease: 'power2.in' }, 0);
        tl.to(rope, { opacity: 0, duration: 0.2 }, 0);
        tl.to(plane, {
            xPercent: 200,
            y: -24,
            opacity: 0,
            duration: exitDuration,
            ease: 'power2.in'
        }, 0.15);
    };

    ArkAirplaneCarousel.prototype.updateDots = function (index) {
        this.el.dots.forEach(function (dot, i) {
            dot.classList.toggle('active', i === index);
            dot.setAttribute('aria-selected', i === index ? 'true' : 'false');
        });
    };

    ArkAirplaneCarousel.prototype.touchSetup = function (gsap) {
        var self = this;
        var viewport = document.querySelector('#ark-services .ark-airplane-viewport');
        if (!viewport) return;

        viewport.addEventListener('touchstart', function (e) {
            if (!e.touches[0]) return;
            self.touchStartX = e.touches[0].clientX;
            self.touchStartT = Date.now();
        }, { passive: true });

        viewport.addEventListener('touchend', function (e) {
            if (!e.changedTouches[0]) return;
            self.touchEndX = e.changedTouches[0].clientX;
            self.touchEndT = Date.now();
            var dx = self.touchStartX - self.touchEndX;
            var dt = Math.max(self.touchEndT - self.touchStartT, 1);
            var velocity = Math.abs(dx) / dt;
            if (Math.abs(dx) > 40 || velocity > 0.3) {
                if (dx > 0) self.next(gsap);
                else self.prev(gsap);
            }
        }, { passive: true });
    };

    function boot() {
        if (typeof window.gsap === 'undefined') {
            setTimeout(boot, 50);
            return;
        }
        new ArkAirplaneCarousel();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
