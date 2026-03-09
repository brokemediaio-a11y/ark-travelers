/**
 * ARK Travelers – Umrah packages: live filtering, sorting, URL params, results count (vanilla JS)
 */

(function () {
    'use strict';

    var grid = document.getElementById('ark-packages-grid');
    var countEl = document.getElementById('ark-packages-count');
    var form = document.getElementById('ark-filters-form');
    var sortSelect = document.getElementById('ark-sort');
    var resetBtn = document.getElementById('ark-reset-filters');

    if (!grid || !countEl) return;

    var cards = Array.from(grid.querySelectorAll('.ark-package-card'));
    if (!cards.length) return;

    function getCardData(card) {
        return {
            card: card,
            price: parseInt(card.getAttribute('data-price'), 10) || 0,
            days: parseInt(card.getAttribute('data-days'), 10) || 0,
            departure: card.getAttribute('data-departure') || '',
            duration: card.getAttribute('data-duration') || '',
            stars: parseInt(card.getAttribute('data-stars'), 10) || 0,
            type: card.getAttribute('data-type') || '',
        };
    }

    function getFilters() {
        var departure = form ? (form.querySelector('#ark-departure-month') && form.querySelector('#ark-departure-month').value) : '';
        var priceMin = form && form.querySelector('#ark-price-min') ? parseInt(form.querySelector('#ark-price-min').value, 10) : 20000;
        var priceMax = form && form.querySelector('#ark-price-max') ? parseInt(form.querySelector('#ark-price-max').value, 10) : 60000;
        var durationChecked = form ? Array.from(form.querySelectorAll('input[name="duration"]:checked')).map(function (c) { return c.value; }) : [];
        var starsChecked = form ? Array.from(form.querySelectorAll('input[name="stars"]:checked')).map(function (c) { return c.value; }) : [];
        var typeChecked = form ? Array.from(form.querySelectorAll('input[name="type"]:checked')).map(function (c) { return c.value; }) : [];
        return {
            departure: departure,
            priceMin: priceMin,
            priceMax: priceMax,
            duration: durationChecked,
            stars: starsChecked,
            type: typeChecked,
        };
    }

    function filterCards() {
        var f = getFilters();
        cards.forEach(function (card) {
            var d = getCardData(card);
            var pass = true;
            if (f.departure && d.departure !== f.departure) pass = false;
            if (d.price < f.priceMin || d.price > f.priceMax) pass = false;
            if (f.duration.length && f.duration.indexOf(d.duration) === -1) pass = false;
            if (f.stars.length && f.stars.indexOf(String(d.stars)) === -1) pass = false;
            if (f.type.length && f.type.indexOf(d.type) === -1) pass = false;
            card.classList.toggle('ark-filtered-out', !pass);
        });
        updateCount();
        updateUrl();
    }

    function updateCount() {
        var visible = cards.filter(function (c) { return !c.classList.contains('ark-filtered-out'); });
        var n = visible.length;
        var text = n === 1 ? '1 package' : n + ' packages';
        if (typeof arkData !== 'undefined' && arkData.themeUrl) {
            countEl.textContent = text;
        } else {
            countEl.textContent = text;
        }
    }

    function sortCards() {
        var order = sortSelect ? sortSelect.value : 'popular';
        var data = cards.map(getCardData);
        if (order === 'price-asc') data.sort(function (a, b) { return a.price - b.price; });
        else if (order === 'price-desc') data.sort(function (a, b) { return b.price - a.price; });
        else if (order === 'duration') data.sort(function (a, b) { return a.days - b.days; });
        else if (order === 'departure') data.sort(function (a, b) { return (a.departure || '').localeCompare(b.departure || ''); });
        data.forEach(function (d) { grid.appendChild(d.card); });
    }

    function updateUrl() {
        if (typeof history === 'undefined' || !history.replaceState) return;
        var f = getFilters();
        var params = new URLSearchParams();
        if (f.departure) params.set('departure', f.departure);
        if (f.priceMin !== 20000) params.set('price_min', f.priceMin);
        if (f.priceMax !== 60000) params.set('price_max', f.priceMax);
        f.duration.forEach(function (v) { params.append('duration', v); });
        f.stars.forEach(function (v) { params.append('stars', v); });
        f.type.forEach(function (v) { params.append('type', v); });
        if (sortSelect && sortSelect.value !== 'popular') params.set('sort', sortSelect.value);
        var qs = params.toString();
        var url = window.location.pathname + (qs ? '?' + qs : '');
        history.replaceState({}, '', url);
    }

    function readUrl() {
        var params = new URLSearchParams(window.location.search);
        if (!form) return;
        var dep = form.querySelector('#ark-departure-month');
        if (dep && params.get('departure')) dep.value = params.get('departure');
        var pm = form.querySelector('#ark-price-min');
        var px = form.querySelector('#ark-price-max');
        if (pm && params.get('price_min')) pm.value = params.get('price_min');
        if (px && params.get('price_max')) px.value = params.get('price_max');
        params.getAll('duration').forEach(function (v) {
            var cb = form.querySelector('input[name="duration"][value="' + v + '"]');
            if (cb) cb.checked = true;
        });
        params.getAll('stars').forEach(function (v) {
            var cb = form.querySelector('input[name="stars"][value="' + v + '"]');
            if (cb) cb.checked = true;
        });
        params.getAll('type').forEach(function (v) {
            var cb = form.querySelector('input[name="type"][value="' + v + '"]');
            if (cb) cb.checked = true;
        });
        if (sortSelect && params.get('sort')) sortSelect.value = params.get('sort');
    }

    function resetFilters() {
        if (!form) return;
        form.reset();
        var pm = form.querySelector('#ark-price-min');
        var px = form.querySelector('#ark-price-max');
        if (pm) pm.value = 20000;
        if (px) px.value = 60000;
        if (sortSelect) sortSelect.value = 'popular';
        filterCards();
        sortCards();
        updateUrl();
    }

    if (form) {
        form.addEventListener('change', filterCards);
        form.addEventListener('input', function (e) {
            if (e.target.id === 'ark-price-min' || e.target.id === 'ark-price-max') filterCards();
        });
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            sortCards();
            updateUrl();
        });
    }
    if (resetBtn) resetBtn.addEventListener('click', resetFilters);

    readUrl();
    filterCards();
    sortCards();
})();
