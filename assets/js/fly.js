/**
 * ARK Travelers – Flight search: trip type toggle, validation, popular route fill, mock results (vanilla JS)
 */

(function () {
    'use strict';

    var form = document.getElementById('ark-fly-form');
    var resultsSection = document.getElementById('ark-fly-results');
    var resultsList = document.getElementById('ark-fly-results-list');
    var returnWrap = document.querySelector('.ark-return-wrap');
    var tripRadios = form ? form.querySelectorAll('input[name="trip"]') : [];

    function initCountryCodeDropdown() {
        var select = document.getElementById('ark-fly-country');
        if (!select || select.dataset.enhanced === 'true') return;

        var options = Array.prototype.slice.call(select.options || []);
        if (!options.length) return;

        var wrapper = document.createElement('div');
        wrapper.className = 'ark-country-select';

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'ark-country-select-button';
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-label', select.getAttribute('aria-label') || 'Country code');

        var list = document.createElement('div');
        list.className = 'ark-country-options';
        list.setAttribute('role', 'listbox');

        function buildOptionDisplay(flag, dialCode) {
            var container = document.createElement('div');
            container.className = 'country-option';

            var img = document.createElement('img');
            img.src = 'https://flagcdn.com/24x18/' + flag + '.png';
            img.alt = '';
            img.loading = 'lazy';
            img.decoding = 'async';

            var span = document.createElement('span');
            span.textContent = dialCode;

            container.appendChild(img);
            container.appendChild(span);
            return container;
        }

        function getSelectedOption() {
            return select.options[select.selectedIndex >= 0 ? select.selectedIndex : 0];
        }

        function syncTrigger() {
            var selected = getSelectedOption();
            if (!selected) return;
            trigger.innerHTML = '';
            trigger.appendChild(
                buildOptionDisplay(
                    (selected.dataset.flag || 'se').toLowerCase(),
                    selected.value || selected.textContent.trim()
                )
            );
        }

        function closeList() {
            wrapper.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
        }

        options.forEach(function (opt, index) {
            var code = (opt.value || opt.textContent || '').trim();
            var flag = (opt.dataset.flag || 'se').toLowerCase();

            var item = document.createElement('button');
            item.type = 'button';
            item.className = 'ark-country-option-item';
            item.setAttribute('role', 'option');
            item.setAttribute('data-value', code);
            item.appendChild(buildOptionDisplay(flag, code));

            item.addEventListener('click', function () {
                select.selectedIndex = index;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                syncTrigger();
                closeList();
            });

            list.appendChild(item);
        });

        trigger.addEventListener('click', function () {
            var opening = !wrapper.classList.contains('is-open');
            wrapper.classList.toggle('is-open', opening);
            trigger.setAttribute('aria-expanded', opening ? 'true' : 'false');
        });

        select.addEventListener('change', syncTrigger);

        document.addEventListener('click', function (event) {
            if (!wrapper.contains(event.target)) {
                closeList();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeList();
            }
        });

        wrapper.appendChild(trigger);
        wrapper.appendChild(list);

        select.classList.add('is-enhanced');
        select.dataset.enhanced = 'true';
        select.parentNode.insertBefore(wrapper, select.nextSibling);
        syncTrigger();
    }

    initCountryCodeDropdown();

    if (!form || !resultsSection || !resultsList) return;

    function toggleReturn() {
        var trip = form.querySelector('input[name="trip"]:checked');
        if (!trip || !returnWrap) return;
        var show = trip.value === 'roundtrip';
        returnWrap.classList.toggle('hidden', !show);
        var returnInput = form.querySelector('#ark-return');
        if (returnInput) returnInput.required = show;
    }

    tripRadios.forEach(function (r) {
        r.addEventListener('change', toggleReturn);
    });
    toggleReturn();

    var today = new Date().toISOString().slice(0, 10);
    var departInput = form.querySelector('#ark-depart');
    if (departInput) departInput.setAttribute('min', today);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var origin = (form.querySelector('#ark-origin') && form.querySelector('#ark-origin').value) || '';
        var destination = (form.querySelector('#ark-destination') && form.querySelector('#ark-destination').value) || '';
        var depart = (form.querySelector('#ark-depart') && form.querySelector('#ark-depart').value) || '';
        if (!origin.trim() || !destination.trim()) {
            resultsList.innerHTML = '<p class="ark-fly-no-results">' + 'Please enter origin and destination.' + '</p>';
            resultsSection.classList.add('is-visible');
            resultsSection.setAttribute('aria-hidden', 'false');
            return;
        }
        resultsList.innerHTML = '<p class="ark-fly-no-results">Searching…</p>';
        resultsSection.classList.add('is-visible');
        resultsSection.setAttribute('aria-hidden', 'false');

        setTimeout(function () {
            renderMockResults(origin, destination, depart);
        }, 800);
    });

    function renderMockResults(origin, destination, depart) {
        var mock = [
            { from: origin, to: destination, date: depart || '—', time: '08:00', duration: '4h 20m', price: 3490 },
            { from: origin, to: destination, date: depart || '—', time: '14:30', duration: '5h 10m', price: 2890 },
            { from: origin, to: destination, date: depart || '—', time: '20:15', duration: '4h 45m', price: 3190 },
        ];
        var html = mock.map(function (f) {
            return (
                '<div class="ark-fly-result-item">' +
                '<div><span class="ark-fly-result-route">' + escapeHtml(f.from) + ' → ' + escapeHtml(f.to) + '</span> ' +
                '<span class="ark-fly-result-meta">' + escapeHtml(f.date) + ' · ' + f.time + ' · ' + f.duration + '</span></div>' +
                '<div class="ark-fly-result-price">SEK ' + f.price + '</div>' +
                '</div>'
            );
        }).join('');
        resultsList.innerHTML = html || '<p class="ark-fly-no-results">No flights found. Try different cities or dates.</p>';
    }

    function escapeHtml(s) {
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    document.querySelectorAll('.ark-route-card[data-origin][data-dest]').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (card.getAttribute('href') !== '#ark-fly-form') return;
            e.preventDefault();
            var origin = document.getElementById('ark-origin');
            var dest = document.getElementById('ark-destination');
            if (origin) origin.value = card.getAttribute('data-origin') || '';
            if (dest) dest.value = card.getAttribute('data-dest') || '';
            document.getElementById('ark-fly-form').scrollIntoView({ behavior: 'smooth' });
        });
    });
})();

/**
 * Airplane window: GSAP + ScrollTrigger – scroll-scrubbed pan + zoom, subtle frame float (turbulence).
 * Performance: transform and opacity only. No inline CSS/JS; runs only on FLY page when elements exist.
 */
(function () {
    'use strict';

    var section = document.getElementById('ark-fly-window');
    var videoWrap = document.getElementById('ark-fly-window-video-wrap');
    var frame = document.getElementById('ark-fly-window-frame');
    var video = document.getElementById('ark-fly-window-video');
    if (!section || !videoWrap || typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    /* Ensure video plays and use fallback if primary source 404s (e.g. airplane-window.mp4 vs Video Project 3.mp4). */
    if (video) {
        video.addEventListener('error', function onVideoError() {
            var fallback = video.getAttribute('data-fallback');
            if (fallback && video.src !== fallback) {
                video.removeEventListener('error', onVideoError);
                video.src = fallback;
                video.load();
            }
        });
        video.addEventListener('canplay', function startOnce() {
            video.removeEventListener('canplay', startOnce);
            video.play().catch(function () {});
        });
    }

    gsap.registerPlugin(ScrollTrigger);

    var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var isNarrow = window.innerWidth < 768;

    if (!reducedMotion) {
        /* Scroll-scrubbed pan + zoom: scroll down = zoom in + pan; scroll up = zoom out + pan back.
           Milder values on small viewports for mobile-safe, calm feel. */
        var endScale = isNarrow ? 1.1 : 1.22;
        var endX = isNarrow ? 16 : 40;

        gsap.fromTo(
            videoWrap,
            { scale: 1, x: 0 },
            {
                scale: endScale,
                x: endX,
                ease: 'none',
                scrollTrigger: {
                    trigger: section,
                    start: 'top center',
                    end: 'bottom center',
                    scrub: 1.2,
                },
            }
        );

        /* Subtle vertical floating (turbulence) on window frame: only on desktop for calm, luxury feel. */
        if (!isNarrow && frame) {
            gsap.to(frame, {
                y: -8,
                duration: 4,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
            });
        }
    }
})();
