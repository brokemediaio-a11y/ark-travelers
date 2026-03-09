/**
 * ARK Travelers – Homepage: parallax (3 speeds), counters, testimonial carousel (vanilla JS)
 */

(function () {
    'use strict';

    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /**
     * Parallax: 3 depth speeds (0.3, 0.6, 1.0) via data-speed + content layering effect
     */
    function initParallax() {
        if (reduced) return;
        var layers = document.querySelectorAll('.ark-parallax-layer[data-speed]');
        var hero = document.querySelector('.ark-hero, .ark-fly-hero-banner');
        var heroContent = hero ? hero.querySelector('.ark-hero-content, .ark-fly-hero-content') : null;
        // Sections that get subtle follow-on parallax after the hero.
        // The trust bar is kept out of this group so it scrolls as a
        // simple strip below the video, not as part of the hero stack.
        var sectionsAfterHero = document.querySelectorAll('.ark-services, .ark-featured-package, .ark-fly-search');
        
        if (!layers.length && !hero) return;

        var ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                var scrollY = window.pageYOffset;
                var windowHeight = window.innerHeight;
                
                // Parallax layers (background images)
                layers.forEach(function (layer) {
                    var parent = layer.closest('.ark-hero-parallax, .ark-fly-hero-parallax, .ark-featured-package, .ark-parallax');
                    if (!parent) return;
                    var rect = parent.getBoundingClientRect();
                    var speed = parseFloat(layer.getAttribute('data-speed')) || 0.5;
                    // For hero backgrounds, move up on scroll (negative offset = background goes up as user scrolls down)
                    if (parent.classList.contains('ark-hero-parallax') || parent.classList.contains('ark-fly-hero-parallax')) {
                        var heroRect = parent.closest('.ark-hero, .ark-fly-hero-banner');
                        if (heroRect) {
                            var heroTop = heroRect.getBoundingClientRect().top;
                            // Move background up as hero scrolls up: heroTop goes 0 -> negative, offset = heroTop * speed (negative = up)
                            var offset = heroTop * speed * 0.5;
                            layer.style.transform = 'translate3d(0, ' + offset + 'px, 0)';
                        } else {
                            var offset = (rect.top + rect.height / 2 - windowHeight / 2) * speed * 0.15;
                            layer.style.transform = 'translate3d(0, ' + offset + 'px, 0)';
                        }
                    } else {
                        var offset = (rect.top + rect.height / 2 - windowHeight / 2) * speed * 0.15;
                        layer.style.transform = 'translate3d(0, ' + offset + 'px, 0)';
                    }
                });
                
                // Hero content parallax (moves slower, stays visible longer)
                if (hero && heroContent) {
                    var heroRect = hero.getBoundingClientRect();
                    if (heroRect.bottom > 0) {
                        var heroTop = heroRect.top;
                        var heroHeight = heroRect.height;
                        if (heroTop < windowHeight && heroRect.bottom > 0) {
                            var heroProgress = Math.min(Math.max(0, (windowHeight - heroTop) / heroHeight), 1);
                            // Content moves with hero (heroTop); same direction as bg, slower
                            var contentOffset = heroTop * 0.2;
                            heroContent.style.transform = 'translate3d(0, ' + contentOffset + 'px, 0)';
                            heroContent.style.opacity = Math.max(1 - heroProgress * 0.3, 0.7);
                        }
                    } else {
                        heroContent.style.transform = '';
                        heroContent.style.opacity = '';
                    }
                }
                
                // Sections scroll normally - no layering effect that causes overlap
                sectionsAfterHero.forEach(function (section) {
                    var rect = section.getBoundingClientRect();
                    // Only apply subtle parallax, don't force z-index changes
                    if (rect.top < windowHeight && rect.bottom > 0) {
                        // Subtle parallax effect for form section
                        if (section.classList.contains('ark-fly-search')) {
                            var progress = Math.min(Math.max(0, (windowHeight - rect.top) / windowHeight), 1);
                            var offset = (rect.top - windowHeight) * 0.05;
                            section.style.transform = 'translate3d(0, ' + offset + 'px, 0)';
                        }
                    } else {
                        section.style.transform = '';
                    }
                });
                
                ticking = false;
            });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /**
     * Counter: animate from 0 to data-ark-counter value
     */
    function initCounters() {
        var counters = document.querySelectorAll('[data-ark-counter]');
        if (!counters.length || reduced) return;

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    var el = entry.target;
                    var target = parseInt(el.getAttribute('data-ark-counter'), 10);
                    if (isNaN(target)) return;
                    observer.unobserve(el);
                    animateCounter(el, 0, target, 2000);
                });
            },
            { threshold: 0.3 }
        );
        counters.forEach(function (el) { observer.observe(el); });
    }

    function animateCounter(el, from, to, duration) {
        var start = null;
        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            var ease = 1 - Math.pow(1 - progress, 2);
            var value = Math.round(from + (to - from) * ease);
            el.textContent = value;
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    /**
     * Testimonials polaroid: filter (desktop), modal, show more (desktop 2 rows), mobile slideshow + swipe
     */
    function initTestimonialsPolaroid() {
        var section = document.getElementById('ark-testimonials');
        if (!section) return;

        var filters = section.querySelectorAll('.testimonials-filter');
        var cards = section.querySelectorAll('.testimonials-masonry .polaroid-card');
        var modal = document.getElementById('testimonial-modal');
        var modalTitle = modal ? modal.querySelector('.polaroid-modal-title') : null;
        var modalLocation = modal ? modal.querySelector('.polaroid-modal-location') : null;
        var modalBody = modal ? modal.querySelector('.polaroid-modal-body') : null;
        var modalClose = modal ? modal.querySelector('.polaroid-modal-close') : null;
        var modalBackdrop = modal ? modal.querySelector('.polaroid-modal-backdrop') : null;

        function setFilter(activeFilter) {
            filters.forEach(function (f) {
                var isActive = f.getAttribute('data-filter') === activeFilter;
                f.classList.toggle('is-active', isActive);
                f.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });
            cards.forEach(function (card) {
                var cats = card.getAttribute('data-categories') || '';
                var list = cats.split(',').map(function (c) { return c.trim(); });
                var show = activeFilter === 'all' || list.indexOf(activeFilter) !== -1;
                card.style.display = show ? '' : 'none';
            });
        }

        if (filters.length) {
            filters.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    setFilter(this.getAttribute('data-filter'));
                });
            });
        }

        function openModal(card) {
            if (!modal || !modalTitle || !modalBody) return;
            modalTitle.textContent = card.getAttribute('data-name') || '';
            if (modalLocation) modalLocation.textContent = card.getAttribute('data-location') || '';
            modalBody.textContent = card.getAttribute('data-full') || '';
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            if (modal) modal.setAttribute('aria-hidden', 'true');
        }

        cards.forEach(function (card, i) {
            card.style.setProperty('--polaroid-index', i);
            card.addEventListener('click', function () { openModal(this); });
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(this); }
            });
        });

        var mobileCards = section.querySelectorAll('.testimonials-mobile .polaroid-card-slide');
        mobileCards.forEach(function (card) {
            card.addEventListener('click', function () { openModal(this); });
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(this); }
            });
        });

        if (modalClose) modalClose.addEventListener('click', closeModal);
        if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);
        if (modal) {
            modal.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal();
            });
        }

        // Staggered Polaroid Reveal Animation - paste-on-wall effect
        var revealCards = section.querySelectorAll('.polaroid-reveal');
        var STAGGER_DELAY = 80; // milliseconds between each card
        var hasAnimated = false;
        
        if (revealCards.length && !reduced) {
            // Observe the container (section) instead of individual cards
            var observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting || hasAnimated) return;
                        hasAnimated = true;
                        
                        // Animate each card with stagger delay
                        revealCards.forEach(function (card, index) {
                            setTimeout(function () {
                                card.classList.add('reveal-visible');
                                
                                // Photo develop effect (fade in photo) with additional delay
                                var photo = card.querySelector('.polaroid-photo');
                                if (photo) {
                                    setTimeout(function () {
                                        photo.classList.add('polaroid-photo-develop');
                                    }, 200);
                                }
                            }, index * STAGGER_DELAY);
                        });
                        
                        // Unobserve after animation starts
                        observer.unobserve(entry.target);
                    });
                },
                { threshold: 0.1, rootMargin: '0px 0px -100px 0px' }
            );
            observer.observe(section);
        } else if (revealCards.length) {
            // Reduced motion: show all cards immediately
            revealCards.forEach(function (el) { 
                el.classList.add('reveal-visible');
                var photo = el.querySelector('.polaroid-photo');
                if (photo) photo.classList.add('polaroid-photo-develop');
            });
        }

        var showMoreBtn = document.getElementById('testimonials-show-more');
        var masonryWrap = document.getElementById('testimonials-masonry-wrap');
        if (showMoreBtn && masonryWrap) {
            var moreText = showMoreBtn.getAttribute('data-more') || 'Show more stories';
            var lessText = showMoreBtn.getAttribute('data-less') || 'Show less';
            showMoreBtn.addEventListener('click', function () {
                var expanded = masonryWrap.classList.toggle('is-expanded');
                showMoreBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                showMoreBtn.textContent = expanded ? lessText : moreText;
            });
        }

        var track = document.getElementById('testimonials-slideshow-track');
        var viewport = document.getElementById('testimonials-slideshow-viewport');
        var dotsContainer = document.getElementById('testimonials-slideshow-dots');
        if (track && viewport && dotsContainer) {
            var slides = track.querySelectorAll('.polaroid-slide');
            var slideCount = slides.length;
            if (slideCount < 2) return;
            var currentSlide = 0;
            var touchStartX = 0;
            var touchEndX = 0;
            var autoAdvanceInterval = null;
            var AUTO_ADVANCE_MS = 5000;

            for (var d = 0; d < slideCount; d++) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', 'Slide ' + (d + 1));
                dot.setAttribute('data-index', d);
                if (d === 0) dot.classList.add('is-active');
                dotsContainer.appendChild(dot);
            }
            var dotButtons = dotsContainer.querySelectorAll('button');

            /* One polaroid per view on mobile: track = slideCount * 100% of viewport, each slide = 100% viewport */
            var slidePct = 100 / slideCount;
            track.style.width = (slideCount * 100) + '%';
            slides.forEach(function (s) {
                s.style.flex = '0 0 ' + slidePct + '%';
                s.style.width = slidePct + '%';
            });

            function updateSlideshow() {
                currentSlide = (currentSlide + slideCount) % slideCount;
                var offset = -currentSlide * slidePct;
                track.style.transform = 'translate3d(' + offset + '%, 0, 0)';
                dotButtons.forEach(function (btn, i) {
                    btn.classList.toggle('is-active', i === currentSlide);
                });
            }

            function goNext() {
                currentSlide++;
                updateSlideshow();
                resetAutoAdvance();
            }

            function resetAutoAdvance() {
                if (autoAdvanceInterval) clearInterval(autoAdvanceInterval);
                autoAdvanceInterval = setInterval(goNext, AUTO_ADVANCE_MS);
            }

            dotButtons.forEach(function (btn, i) {
                btn.addEventListener('click', function () {
                    currentSlide = i;
                    updateSlideshow();
                    resetAutoAdvance();
                });
            });

            viewport.addEventListener('touchstart', function (e) {
                touchStartX = e.touches[0].clientX;
            }, { passive: true });
            viewport.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].clientX;
                var diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) currentSlide++;
                    else currentSlide--;
                    updateSlideshow();
                    resetAutoAdvance();
                }
            }, { passive: true });

            updateSlideshow();
            resetAutoAdvance();
        }
    }

    /**
     * Services carousel: plane + card fly in/out; next/prev and swipe trigger exit then enter
     */
    function initServicesCarousel() {
        var track = document.getElementById('ark-services-track');
        if (!track) return;
        var slides = track.querySelectorAll('.ark-services-slide');
        if (slides.length < 2) return;
        var prevBtn = document.querySelector('.ark-services-prev');
        var nextBtn = document.querySelector('.ark-services-next');
        var viewport = document.querySelector('.ark-services-viewport');
        var current = 0;
        var animating = false;

        function updateTrack(instant) {
            current = (current + slides.length) % slides.length;
            var offset = (current * 100) / slides.length;
            if (instant) {
                track.style.transition = 'none';
                track.style.transform = 'translate3d(-' + offset + '%, 0, 0)';
                track.offsetHeight;
                track.style.transition = '';
            } else {
                track.style.transform = 'translate3d(-' + offset + '%, 0, 0)';
            }
        }

        function goNext() {
            if (animating) return;
            if (reduced) {
                current++;
                updateTrack();
                return;
            }
            animating = true;
            var leaving = slides[current];
            var delivery = leaving ? leaving.querySelector('.ark-service-delivery') : null;
            leaving.classList.add('ark-exit-right');

            function onExitEnd() {
                if (!delivery) { animating = false; return; }
                delivery.removeEventListener('animationend', onExitEnd);
                leaving.classList.remove('ark-exit-right');
                current++;
                updateTrack(true);
                var entering = slides[current];
                var enterDelivery = entering ? entering.querySelector('.ark-service-delivery') : null;
                entering.classList.add('ark-enter-left');
                function onEnterEnd() {
                    if (!enterDelivery) { animating = false; return; }
                    enterDelivery.removeEventListener('animationend', onEnterEnd);
                    entering.classList.remove('ark-enter-left');
                    animating = false;
                }
                enterDelivery.addEventListener('animationend', onEnterEnd, { once: true });
            }
            delivery.addEventListener('animationend', onExitEnd, { once: true });
        }

        function goPrev() {
            if (animating) return;
            if (reduced) {
                current--;
                updateTrack();
                return;
            }
            animating = true;
            var leaving = slides[current];
            var delivery = leaving ? leaving.querySelector('.ark-service-delivery') : null;
            leaving.classList.add('ark-exit-left');

            function onExitEnd() {
                if (!delivery) { animating = false; return; }
                delivery.removeEventListener('animationend', onExitEnd);
                leaving.classList.remove('ark-exit-left');
                current--;
                updateTrack(true);
                var entering = slides[current];
                var enterDelivery = entering ? entering.querySelector('.ark-service-delivery') : null;
                entering.classList.add('ark-enter-right');
                function onEnterEnd() {
                    if (!enterDelivery) { animating = false; return; }
                    enterDelivery.removeEventListener('animationend', onEnterEnd);
                    entering.classList.remove('ark-enter-right');
                    animating = false;
                }
                enterDelivery.addEventListener('animationend', onEnterEnd, { once: true });
            }
            delivery.addEventListener('animationend', onExitEnd, { once: true });
        }

        if (prevBtn) prevBtn.addEventListener('click', goPrev);
        if (nextBtn) nextBtn.addEventListener('click', goNext);

        if (viewport) {
            var touchStartX = 0;
            viewport.addEventListener('touchstart', function (e) {
                touchStartX = e.touches[0].clientX;
            }, { passive: true });
            viewport.addEventListener('touchend', function (e) {
                var touchEndX = e.changedTouches[0].clientX;
                var diff = touchStartX - touchEndX;
                if (Math.abs(diff) < 40) return;
                if (diff > 0) goNext();
                else goPrev();
            }, { passive: true });
        }

        updateTrack();
    }

    /**
     * Services parallax: desktop = vertical (1 & 3 up, 2 down); mobile = horizontal left–right–left on scroll
     */
    function initServicesParallax() {
        var section = document.querySelector('#ark-services.ark-services-parallax');
        if (!section) return;
        var cards = section.querySelectorAll('.ark-service-parallax-card');
        if (cards.length < 3) return;
        var speed = reduced ? 0 : 0.22;
        var maxOffset = 72;
        var mobileAmplitude = 28;
        var mobileBreakpoint = 992;
        var ticking = false;
        var mql = window.matchMedia && window.matchMedia('(max-width: ' + mobileBreakpoint + 'px)');

        function update() {
            var rect = section.getBoundingClientRect();
            var windowHeight = window.innerHeight;
            var isMobile = mql ? mql.matches : window.innerWidth <= mobileBreakpoint;

            if (rect.bottom < 0 || rect.top > windowHeight) {
                if (!isMobile) {
                    cards.forEach(function (c) { c.style.transform = ''; });
                }
                ticking = false;
                return;
            }

            if (isMobile && !reduced) {
                // Horizontal parallax: progress 0→1 as section scrolls through viewport; left → right → left via -cos(progress * 2π)
                var progress = (windowHeight - rect.top) / (windowHeight + rect.height);
                progress = Math.max(0, Math.min(1, progress));
                var x = -mobileAmplitude * Math.cos(progress * 2 * Math.PI);
                cards.forEach(function (card) {
                    var dir = card.getAttribute('data-parallax');
                    var tx = (dir === 'down') ? -x : x;
                    card.style.transform = 'translate3d(' + tx + 'px, 0, 0)';
                });
            } else {
                // Desktop: vertical parallax
                var center = rect.top + rect.height * 0.5 - windowHeight * 0.5;
                var offset = speed === 0 ? 0 : Math.max(-maxOffset, Math.min(maxOffset, center * speed));
                cards.forEach(function (card) {
                    var dir = card.getAttribute('data-parallax');
                    var y = dir === 'down' ? -offset : offset;
                    card.style.transform = 'translate3d(0, ' + y + 'px, 0)';
                });
            }
            ticking = false;
        }

        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(update);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        if (mql && mql.addEventListener) {
            mql.addEventListener('change', function () {
                update();
            });
        }
        window.addEventListener('resize', onScroll);
        update();
    }

    /**
     * Section-level parallax: subtle translateY, throttled for smoothness
     */
    function initSectionParallax() {
        var sections = document.querySelectorAll('[data-parallax-section]');
        if (!sections.length || reduced) return;
        var speed = 0.06;
        var ticking = false;
        var lastScroll = 0;

        function update() {
            var windowHeight = window.innerHeight;
            sections.forEach(function (section) {
                var rect = section.getBoundingClientRect();
                if (rect.bottom < -100 || rect.top > windowHeight + 100) return;
                var offset = (rect.top + rect.height * 0.5 - windowHeight * 0.5) * speed;
                section.style.transform = 'translate3d(0, ' + Math.round(offset) + 'px, 0)';
            });
            ticking = false;
        }

        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(update);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        update();
    }

    /**
     * Elegant Scandinavian-inspired parallax: refined, smooth, and sophisticated
     * Skipped on homepage when GSAP overlapping video parallax is used (video-overlap-parallax.js).
     */
    function initVideoParallax() {
        var videoSection = document.getElementById('ark-video-section');
        if (videoSection && typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined') return;
        var wrapper = videoSection ? videoSection.querySelector('.ark-video-wrapper') : null;
        var gif = videoSection ? videoSection.querySelector('.ark-video-parallax') : null;
        if (!videoSection || !wrapper || !gif || reduced) return;

        // Refined parallax parameters - subtle and elegant
        var parallaxSpeed = 0.4; // Gentle vertical movement (lower = more subtle)
        var scaleIntensity = 0.08; // Very subtle scale effect
        var baseScale = 1.0;
        var maxOffset = 150; // Moderate max offset for elegance
        var hasRevealed = false;
        var ticking = false;
        var lastScrollY = window.pageYOffset || window.scrollY;

        // Scandinavian-inspired easing (smooth, natural)
        function easeOutQuart(t) {
            return 1 - Math.pow(1 - t, 4);
        }

        function easeInOutQuart(t) {
            return t < 0.5 ? 8 * t * t * t * t : 1 - Math.pow(-2 * t + 2, 4) / 2;
        }

        // Smooth velocity-based easing
        function smoothStep(t) {
            return t * t * (3 - 2 * t);
        }

        function update() {
            var rect = videoSection.getBoundingClientRect();
            var windowHeight = window.innerHeight;
            var windowCenter = windowHeight * 0.5;
            var currentScrollY = window.pageYOffset || window.scrollY;
            var scrollDelta = Math.abs(currentScrollY - lastScrollY);
            lastScrollY = currentScrollY;
            
            // Calculate section visibility
            var sectionTop = rect.top;
            var sectionHeight = rect.height;
            var sectionCenter = sectionTop + sectionHeight * 0.5;
            var sectionBottom = rect.bottom;
            
            // Elegant reveal: starts when section enters viewport, fully revealed at center
            var revealStart = windowHeight * 0.9; // Start revealing when 90% down viewport
            var revealEnd = windowHeight * 0.4; // Fully revealed when 40% down viewport
            var revealProgress = 0;
            
            if (sectionTop < revealStart && sectionBottom > revealEnd) {
                var revealRange = revealStart - revealEnd;
                var revealPosition = revealStart - sectionTop;
                revealProgress = Math.max(0, Math.min(1, revealPosition / revealRange));
                revealProgress = easeOutQuart(revealProgress); // Smooth reveal
            }
            
            // Trigger elegant reveal animation once
            if (revealProgress > 0.15 && !hasRevealed) {
                hasRevealed = true;
                gif.classList.add('is-visible');
                wrapper.classList.add('is-visible');
            }
            
            // Reset when scrolled past
            if (rect.top > windowHeight + 100) {
                if (hasRevealed) {
                    hasRevealed = false;
                    gif.classList.remove('is-visible');
                    wrapper.classList.remove('is-visible');
                }
                ticking = false;
                return;
            }
            
            // Only apply parallax when section is in viewport
            if (rect.bottom < -100) {
                ticking = false;
                return;
            }

            // Calculate distance from viewport center with smooth normalization
            var distanceFromCenter = sectionCenter - windowCenter;
            var normalizedDistance = Math.max(-windowHeight * 1.2, Math.min(windowHeight * 1.2, distanceFromCenter));
            var normalizedRatio = normalizedDistance / (windowHeight * 1.2);
            
            // Elegant parallax offset with smooth easing
            var rawOffset = normalizedDistance * parallaxSpeed;
            var easedRatio = smoothStep(Math.abs(normalizedRatio));
            var offsetY = rawOffset * (1 - easedRatio * 0.3); // Slight reduction at extremes
            offsetY = Math.max(-maxOffset, Math.min(maxOffset, offsetY));
            
            // Very subtle scale effect - elegant zoom
            var distanceRatio = Math.abs(distanceFromCenter) / windowHeight;
            var scale = baseScale - (distanceRatio * scaleIntensity);
            scale = Math.max(0.96, Math.min(1.04, scale)); // Very subtle: 96% to 104%
            
            // Calculate opacity based on reveal progress and position
            var positionOpacity = 1;
            if (sectionTop > windowHeight * 0.7) {
                // Fade in as entering
                positionOpacity = Math.max(0, (windowHeight * 0.7 - sectionTop) / (windowHeight * 0.3));
            } else if (sectionBottom < windowHeight * 0.3) {
                // Fade out as leaving
                positionOpacity = Math.max(0, (sectionBottom - windowHeight * 0.3) / (windowHeight * 0.3));
            }
            
            var finalOpacity = hasRevealed ? positionOpacity : Math.max(0, revealProgress * positionOpacity);
            
            // Apply transforms with hardware acceleration
            var translateX = -50;
            var translateY = -50 + offsetY;
            
            gif.style.transform = 'translate3d(' + translateX + '%, ' + translateY + '%, 0) scale(' + scale.toFixed(3) + ')';
            gif.style.opacity = finalOpacity;
            
            // Subtle brightness adjustment for depth
            var brightness = 1 - (distanceRatio * 0.05); // Very subtle
            gif.style.filter = 'blur(0px) brightness(' + brightness.toFixed(3) + ') contrast(1)';
            
            ticking = false;
        }

        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(update);
        }

        // Intersection Observer for elegant reveal
        var observer = new IntersectionObserver(
            function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting && !hasRevealed) {
                        update();
                    }
                });
            },
            { threshold: 0.2, rootMargin: '0px 0px -150px 0px' }
        );
        observer.observe(videoSection);

        // Smooth scroll handling
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll, { passive: true });
        update();
    }

    function init() {
        initParallax();
        initSectionParallax();
        initVideoParallax();
        initCounters();
        initServicesCarousel();
        initServicesParallax();
        initTestimonialsPolaroid();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
