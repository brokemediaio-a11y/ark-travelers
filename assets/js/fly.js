/**
 * ARK Travelers – Flight search: trip type toggle, airport autocomplete, live search via WordPress AJAX (vanilla JS)
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

    var cfg = window.arkFly || {};
    var t = cfg.i18n || {};
    var contact = cfg.contact || {};
    var originInput = form.querySelector('#ark-origin');
    var destInput = form.querySelector('#ark-destination');
    var originIata = form.querySelector('#ark-origin-iata');
    var destIata = form.querySelector('#ark-destination-iata');
    var submitBtn = form.querySelector('button[type="submit"]');
    var busy = false;

    /* "We found %1$s options for %2$s", "%s stops" … */
    function fmt(str, a, b) {
        return String(str || '')
            .replace('%1$s', a)
            .replace('%2$s', b)
            .replace('%s', a);
    }

    function el(tag, className, text) {
        var node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined && text !== null) node.textContent = text;
        return node;
    }

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

    /* Dates: no past departures, return never before departure. */
    var today = new Date();
    today = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    var departInput = form.querySelector('#ark-depart');
    var returnDate = form.querySelector('#ark-return');
    if (departInput) departInput.setAttribute('min', today);
    if (returnDate) returnDate.setAttribute('min', today);
    if (departInput && returnDate) {
        departInput.addEventListener('change', function () {
            returnDate.setAttribute('min', departInput.value || today);
            if (returnDate.value && departInput.value && returnDate.value < departInput.value) {
                returnDate.value = departInput.value;
            }
        });
    }

    /* ---------- Airport / city autocomplete ---------- */

    var placesCache = {};

    function fetchPlaces(query, signal) {
        var key = query.toLowerCase();
        if (placesCache[key]) return Promise.resolve(placesCache[key]);
        var url = cfg.ajaxUrl + '?action=ark_fly_places&nonce=' + encodeURIComponent(cfg.nonce) + '&q=' + encodeURIComponent(query);
        return fetch(url, { credentials: 'same-origin', signal: signal })
            .then(function (res) { return res.json(); })
            .then(function (json) {
                var places = json && json.success && json.data && json.data.places ? json.data.places : [];
                placesCache[key] = places;
                return places;
            });
    }

    function placeLabel(p) {
        var base = p.type === 'city' ? (p.city || p.name) : p.name;
        return base + ' (' + p.iata + ')';
    }

    function initPlaceAutocomplete(input, hidden) {
        if (!input || !hidden) return;

        var list = el('ul', 'ark-places');
        list.setAttribute('role', 'listbox');
        list.id = input.id + '-listbox';
        list.hidden = true;
        input.parentNode.classList.add('ark-has-places');
        input.parentNode.appendChild(list);
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-expanded', 'false');
        input.setAttribute('aria-controls', list.id);

        var items = [];
        var active = -1;
        var timer = null;
        var controller = null;

        function close() {
            list.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            active = -1;
        }

        function setActive(i) {
            active = i;
            items.forEach(function (li, idx) {
                li.classList.toggle('is-active', idx === i);
                li.setAttribute('aria-selected', idx === i ? 'true' : 'false');
            });
        }

        function choose(p) {
            input.value = placeLabel(p);
            hidden.value = p.iata;
            close();
        }

        function render(places) {
            list.innerHTML = '';
            items = [];
            if (!places.length) {
                var empty = el('li', 'ark-places-empty', t.noMatches || 'No matching airports');
                list.appendChild(empty);
            }
            places.forEach(function (p) {
                var li = el('li', 'ark-place');
                li.setAttribute('role', 'option');
                li.appendChild(el('span', 'ark-place-name', placeLabel(p)));
                var sub = p.type === 'city' ? (t.allAirports || 'all airports') : p.city;
                if (p.country) sub = (sub ? sub + ', ' : '') + p.country;
                if (sub) li.appendChild(el('span', 'ark-place-sub', sub));
                /* mousedown, not click: fires before the input loses focus. */
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    choose(p);
                });
                list.appendChild(li);
                items.push(li);
            });
            list.hidden = false;
            input.setAttribute('aria-expanded', 'true');
            setActive(-1);
            list._places = places;
        }

        input.addEventListener('input', function () {
            hidden.value = '';
            window.clearTimeout(timer);
            if (controller) controller.abort();
            var q = input.value.trim();
            if (q.length < 2) {
                close();
                return;
            }
            timer = window.setTimeout(function () {
                controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
                fetchPlaces(q, controller ? controller.signal : undefined)
                    .then(function (places) {
                        if (input.value.trim() === q) render(places);
                    })
                    .catch(function () {});
            }, 250);
        });

        input.addEventListener('keydown', function (e) {
            if (list.hidden || !items.length) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActive((active + 1) % items.length);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActive((active - 1 + items.length) % items.length);
            } else if (e.key === 'Enter' && active >= 0) {
                e.preventDefault();
                choose(list._places[active]);
            } else if (e.key === 'Escape') {
                close();
            }
        });

        input.addEventListener('blur', function () {
            window.setTimeout(close, 100);
        });
    }

    initPlaceAutocomplete(originInput, originIata);
    initPlaceAutocomplete(destInput, destIata);

    /* If the visitor typed a city but never picked from the list, resolve it ourselves. */
    function resolveIata(input, hidden) {
        if (hidden.value) return Promise.resolve(hidden.value);
        var text = input.value.trim();
        var inParens = /\(([A-Za-z]{3})\)\s*$/.exec(text);
        if (inParens) return Promise.resolve(inParens[1].toUpperCase());
        if (/^[A-Za-z]{3}$/.test(text)) return Promise.resolve(text.toUpperCase());
        if (text.length < 2) return Promise.resolve('');
        return fetchPlaces(text).then(function (places) {
            if (!places.length) return '';
            input.value = placeLabel(places[0]);
            hidden.value = places[0].iata;
            return places[0].iata;
        }).catch(function () { return ''; });
    }

    /* ---------- Results ---------- */

    function showResults(node) {
        resultsList.innerHTML = '';
        resultsList.appendChild(node);
        resultsSection.classList.add('is-visible');
        resultsSection.setAttribute('aria-hidden', 'false');
    }

    function scrollToResults() {
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        resultsSection.scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
    }

    function contactButtons(message) {
        var wrap = el('div', 'ark-flight-card-cta');
        var wa = el('a', 'btn-primary ark-flight-contact', t.contactCta || 'Contact for price & booking');
        wa.href = 'https://wa.me/' + (contact.whatsapp || '') + '?text=' + encodeURIComponent(message);
        wa.target = '_blank';
        wa.rel = 'noopener';
        wrap.appendChild(wa);
        if (contact.tel) {
            var call = el('a', 'ark-flight-call', (t.callUs || 'Call us') + ' ' + (contact.display || ''));
            call.href = 'tel:' + contact.tel;
            wrap.appendChild(call);
        }
        return wrap;
    }

    function showMessage(text, withContact) {
        var box = el('div', 'ark-fly-no-results');
        box.appendChild(el('p', null, text));
        if (withContact) {
            box.appendChild(contactButtons(
                (t.waGreeting || '') + '\n' + originInput.value + ' → ' + destInput.value + ' (' + (departInput ? departInput.value : '') + ')'
            ));
        }
        showResults(box);
    }

    function showSearching() {
        var box = el('div', 'ark-fly-searching');
        box.appendChild(el('span', 'ark-fly-spinner'));
        var text = el('div');
        text.appendChild(el('strong', null, t.searching || 'Searching airlines…'));
        text.appendChild(el('span', null, t.searchingNote || ''));
        box.appendChild(text);
        showResults(box);
    }

    function formatDate(iso) {
        var d = new Date(iso + 'T00:00:00');
        if (isNaN(d.getTime())) return iso;
        try {
            return d.toLocaleDateString(document.documentElement.lang || undefined, { weekday: 'short', day: 'numeric', month: 'short' });
        } catch (err) {
            return iso;
        }
    }

    function stopsText(slice) {
        if (!slice.stops) return t.direct || 'Direct';
        var base = slice.stops === 1 ? (t.oneStop || '1 stop') : fmt(t.nStops || '%s stops', slice.stops);
        return slice.via && slice.via.length ? base + ' ' + fmt(t.via || 'via %s', slice.via.join(', ')) : base;
    }

    function renderLeg(slice, label) {
        var leg = el('div', 'ark-flight-leg');
        leg.appendChild(el('div', 'ark-flight-leg-label', (label ? label + ' · ' : '') + formatDate(slice.date)));

        var times = el('div', 'ark-flight-times');
        times.appendChild(el('strong', null, slice.dep));
        times.appendChild(el('span', 'ark-flight-code', slice.from.iata));
        times.appendChild(el('span', 'ark-flight-line'));
        times.appendChild(el('strong', null, slice.arr));
        if (slice.arrDayOffset > 0) times.appendChild(el('sup', 'ark-flight-plus', '+' + slice.arrDayOffset));
        times.appendChild(el('span', 'ark-flight-code', slice.to.iata));
        leg.appendChild(times);

        var meta = [slice.duration, stopsText(slice), (slice.flights || []).join(', ')].filter(Boolean);
        leg.appendChild(el('div', 'ark-flight-leg-meta', meta.join(' · ')));
        return leg;
    }

    function whatsappMessage(option, search) {
        var lines = [t.waGreeting || '', ''];
        option.slices.forEach(function (s) {
            lines.push(s.from.iata + ' → ' + s.to.iata + ', ' + s.date + ' ' + s.dep + '–' + s.arr + ' (' + (s.flights || []).join(', ') + ')');
        });
        lines.push(option.airline + (option.cabin ? ', ' + option.cabin : ''));
        lines.push((t.waTravellers || 'Travellers') + ': ' + search.travellers);
        if (search.name) lines.push((t.waName || 'Name') + ': ' + search.name);
        return lines.join('\n');
    }

    function renderCard(option, search) {
        var card = el('article', 'ark-flight-card');
        var main = el('div', 'ark-flight-card-main');

        var head = el('div', 'ark-flight-airline');
        if (option.logo) {
            var logo = el('img', 'ark-flight-logo');
            logo.src = option.logo;
            logo.alt = '';
            logo.width = 28;
            logo.height = 28;
            logo.loading = 'lazy';
            head.appendChild(logo);
        }
        head.appendChild(el('span', 'ark-flight-airline-name', option.airline));
        if (option.cabin) head.appendChild(el('span', 'ark-flight-cabin', option.cabin));
        main.appendChild(head);

        var multi = option.slices.length > 1;
        option.slices.forEach(function (s, i) {
            main.appendChild(renderLeg(s, multi ? (i === 0 ? t.outbound || 'Outbound' : t.inbound || 'Return') : ''));
        });

        if (option.bags) {
            main.appendChild(el('div', 'ark-flight-extras', option.bags.checked > 0
                ? fmt(t.checkedBag || '%s checked bag(s)', option.bags.checked)
                : (t.noCheckedBag || 'No checked bag')));
        }

        card.appendChild(main);
        card.appendChild(contactButtons(whatsappMessage(option, search)));
        return card;
    }

    function renderResults(data, search) {
        var total = data.totalOptions || 0;
        var options = data.options || [];
        if (!total || !options.length) {
            showMessage(t.noResults || 'No flights found.', true);
            return;
        }

        var wrap = el('div', 'ark-flight-results');
        var route = originInput.value + ' → ' + destInput.value;
        var heading = el('h2', 'ark-flight-results-title', total === 1
            ? fmt(t.foundOne || 'We found 1 option for %2$s', '1', route)
            : fmt(t.found || 'We found %1$s options for %2$s', String(total), route));
        wrap.appendChild(heading);
        var note = (t.priceNote || '') + (options.length < total ? ' ' + fmt(t.showing || 'Showing the first %s.', String(options.length)) : '');
        if (note.trim()) wrap.appendChild(el('p', 'ark-flight-results-note', note.trim()));
        options.forEach(function (o) {
            wrap.appendChild(renderCard(o, search));
        });
        showResults(wrap);
    }

    /* ---------- Submit ---------- */

    function setBusy(state) {
        busy = state;
        if (submitBtn) submitBtn.disabled = state;
        form.setAttribute('aria-busy', state ? 'true' : 'false');
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (busy) return;
        setBusy(true);
        showSearching();
        scrollToResults();

        var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
        var abortTimer = window.setTimeout(function () {
            if (controller) controller.abort();
        }, 60000);

        Promise.all([resolveIata(originInput, originIata), resolveIata(destInput, destIata)])
            .then(function (codes) {
                if (!codes[0] || !codes[1]) {
                    showMessage(t.chooseAirport || 'Please choose an airport from the list.', false);
                    (codes[0] ? destInput : originInput).focus();
                    return null;
                }
                var body = new URLSearchParams(new FormData(form));
                body.set('origin_iata', codes[0]);
                body.set('destination_iata', codes[1]);
                body.set('action', 'ark_flight_search');
                body.set('nonce', cfg.nonce);
                return fetch(cfg.ajaxUrl, {
                    method: 'POST',
                    body: body,
                    credentials: 'same-origin',
                    signal: controller ? controller.signal : undefined
                })
                    .then(function (res) { return res.json(); })
                    .then(function (json) {
                        if (json && json.success) {
                            var pax = [];
                            var adults = Number(form.adults.value) || 1;
                            var kids = Number(form.children.value) || 0;
                            var infants = Number(form.infants.value) || 0;
                            pax.push(adults + ' adult' + (adults > 1 ? 's' : ''));
                            if (kids) pax.push(kids + ' child' + (kids > 1 ? 'ren' : ''));
                            if (infants) pax.push(infants + ' infant' + (infants > 1 ? 's' : ''));
                            renderResults(json.data, {
                                travellers: pax.join(', '),
                                name: form.contact_name ? form.contact_name.value : ''
                            });
                        } else {
                            var msg = json && json.data && json.data.message ? json.data.message : (t.error || 'Search failed.');
                            showMessage(msg, !(json && json.data && json.data.code === 'invalid_request'));
                        }
                    });
            })
            .catch(function () {
                showMessage(t.error || 'Search failed.', true);
            })
            .then(function () {
                window.clearTimeout(abortTimer);
                setBusy(false);
                resultsSection.focus({ preventScroll: true });
            });
    });

    document.querySelectorAll('.ark-route-card[data-origin][data-dest]').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (card.getAttribute('href') !== '#ark-fly-form') return;
            e.preventDefault();
            if (originInput) originInput.value = card.getAttribute('data-origin') || '';
            if (destInput) destInput.value = card.getAttribute('data-dest') || '';
            if (originIata) originIata.value = card.getAttribute('data-origin-iata') || '';
            if (destIata) destIata.value = card.getAttribute('data-dest-iata') || '';
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
