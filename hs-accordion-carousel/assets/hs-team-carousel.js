/* ============================================================
   HS Vertical Team Carousel — JavaScript
   window.hsInitTeamCarousel(config) — called by each widget instance
   Desktop : two VERTICAL columns, translateY animation
   Mobile  : two HORIZONTAL rows,  translateX animation
   ============================================================ */

(function (global) {
    'use strict';

    var MOBILE_BP = 768;

    function hsInitTeamCarousel(cfg) {
        var sectionEl  = document.getElementById(cfg.sectionId);
        var col1El     = document.getElementById(cfg.col1Id);
        var col2El     = cfg.col2Id ? document.getElementById(cfg.col2Id) : null;
        if (!sectionEl || !col1El) return;

        var track1 = col1El.querySelector('.hs-team-track');
        var track2 = col2El ? col2El.querySelector('.hs-team-track') : null;
        if (!track1) return;

        var speed1     = parseFloat(cfg.speed1) || 0.6;
        var speed2     = parseFloat(cfg.speed2) || 0.6;
        var pauseHover = cfg.pauseOnHover !== false;
        var popupOn    = cfg.popup !== false;
        var popupAnim  = cfg.popupAnim || 'zoom';

        var paused     = false;
        var pos1       = 0;
        var pos2       = 0;
        var half1      = 0;
        var half2      = 0;
        var raf        = null;
        var activeCard = null;

        function isMobile() { return window.innerWidth <= MOBILE_BP; }

        /* ── Duplicate items for seamless loop ── */
        function duplicateTrack(track) {
            var origCards = Array.prototype.slice.call(track.children);
            origCards.forEach(function (card) {
                var clone = card.cloneNode(true);
                clone.setAttribute('aria-hidden', 'true');
                track.appendChild(clone);
            });
        }

        duplicateTrack(track1);
        if (track2) duplicateTrack(track2);

        /* ── Measure half-size (height on desktop, width on mobile) ── */
        function measure() {
            if (isMobile()) {
                /* Horizontal: measure widths */
                var gap1   = parseFloat(getComputedStyle(track1).gap) || 0;
                var cards1 = Array.prototype.slice.call(track1.querySelectorAll('.hs-team-card'));
                var cnt1   = cards1.length / 2;
                var w1     = 0;
                for (var i = 0; i < cnt1; i++) w1 += cards1[i].offsetWidth + gap1;
                half1 = w1;

                if (track2) {
                    var gap2   = parseFloat(getComputedStyle(track2).gap) || 0;
                    var cards2 = Array.prototype.slice.call(track2.querySelectorAll('.hs-team-card'));
                    var cnt2   = cards2.length / 2;
                    var w2     = 0;
                    for (var j = 0; j < cnt2; j++) w2 += cards2[j].offsetWidth + gap2;
                    half2 = w2;
                    pos2  = -half2; /* row 2 starts offset so it scrolls rightward */
                }
            } else {
                /* Vertical: measure heights */
                var gap1v   = parseFloat(getComputedStyle(track1).gap) || 0;
                var cards1v = Array.prototype.slice.call(track1.querySelectorAll('.hs-team-card'));
                var cnt1v   = cards1v.length / 2;
                var h1      = 0;
                for (var a = 0; a < cnt1v; a++) h1 += cards1v[a].offsetHeight + gap1v;
                half1 = h1;

                if (track2) {
                    var gap2v   = parseFloat(getComputedStyle(track2).gap) || 0;
                    var cards2v = Array.prototype.slice.call(track2.querySelectorAll('.hs-team-card'));
                    var cnt2v   = cards2v.length / 2;
                    var h2      = 0;
                    for (var b = 0; b < cnt2v; b++) h2 += cards2v[b].offsetHeight + gap2v;
                    half2 = h2;
                    pos2  = -half2;
                }
            }
        }

        measure();

        /* ── RAF animation loop ── */
        function tick() {
            if (!paused && half1 > 0) {
                if (isMobile()) {
                    /* Row 1: scroll LEFT */
                    pos1 -= speed1;
                    if (Math.abs(pos1) >= half1) pos1 = 0;
                    track1.style.transform = 'translateX(' + pos1 + 'px)';
                    /* Row 2: scroll RIGHT */
                    if (track2 && half2 > 0) {
                        pos2 += speed2;
                        if (pos2 >= 0) pos2 = -half2;
                        track2.style.transform = 'translateX(' + pos2 + 'px)';
                    }
                } else {
                    /* Col 1: scroll UP */
                    pos1 -= speed1;
                    if (Math.abs(pos1) >= half1) pos1 = 0;
                    track1.style.transform = 'translateY(' + pos1 + 'px)';
                    /* Col 2: scroll DOWN */
                    if (track2 && half2 > 0) {
                        pos2 += speed2;
                        if (pos2 >= 0) pos2 = -half2;
                        track2.style.transform = 'translateY(' + pos2 + 'px)';
                    }
                }
            }
            raf = requestAnimationFrame(tick);
        }

        raf = requestAnimationFrame(tick);

        /* ── Remeasure on resize (axis may change) ── */
        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                /* Clear transforms so offsetWidth/Height are clean */
                track1.style.transform = '';
                if (track2) track2.style.transform = '';
                pos1 = 0;
                pos2 = 0;
                measure();
                pos2 = half2 ? -half2 : 0;
            }, 150);
        });

        /* ── Pause on section hover ── */
        if (pauseHover) {
            sectionEl.addEventListener('mouseenter', function () { paused = true; });
            sectionEl.addEventListener('mouseleave', function () {
                paused = false;
                if (activeCard) {
                    activeCard.classList.remove('hs-tc-active');
                    activeCard = null;
                }
            });
        }

        /* ── Popup ── */
        if (!popupOn) return;

        var overlay = document.createElement('div');
        overlay.className  = 'hs-tc-overlay';
        overlay.setAttribute('data-anim',   popupAnim);
        if (cfg.widgetId) overlay.setAttribute('data-widget', cfg.widgetId);
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', 'Team member details');
        overlay.innerHTML =
            '<div class="hs-tc-modal-wrap">' +
                '<button class="hs-tc-close" aria-label="Close">' +
                    '<svg width="14" height="14" viewBox="0 0 14 14" fill="none">' +
                    '<path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>' +
                    '</svg>' +
                '</button>' +
                '<div class="hs-tc-modal">' +
                    '<div class="hs-tc-modal-text">' +
                        '<p class="hs-tc-modal-name"></p>' +
                        '<p class="hs-tc-modal-position"></p>' +
                        '<p class="hs-tc-modal-desc"></p>' +
                    '</div>' +
                    '<div class="hs-tc-modal-img"><img src="" alt="" loading="lazy"></div>' +
                '</div>' +
            '</div>';

        document.body.appendChild(overlay);

        var modal    = overlay.querySelector('.hs-tc-modal');
        var wrap     = overlay.querySelector('.hs-tc-modal-wrap');
        var closeBtn = overlay.querySelector('.hs-tc-close');
        var nameEl   = overlay.querySelector('.hs-tc-modal-name');
        var posEl    = overlay.querySelector('.hs-tc-modal-position');
        var descEl   = overlay.querySelector('.hs-tc-modal-desc');
        var imgEl    = overlay.querySelector('.hs-tc-modal-img img');

        if (cfg.overlayBg)  overlay.style.setProperty('--hst-overlay-bg',  cfg.overlayBg);
        if (cfg.popupBg)    overlay.style.setProperty('--hst-popup-bg',    cfg.popupBg);
        if (cfg.popupWidth) overlay.style.setProperty('--hst-popup-width', cfg.popupWidth);

        function openPopup(card) {
            nameEl.textContent = card.getAttribute('data-name')     || '';
            posEl.textContent  = card.getAttribute('data-position') || '';
            descEl.textContent = card.getAttribute('data-desc')     || '';
            imgEl.src          = card.getAttribute('data-img')      || '';
            imgEl.alt          = nameEl.textContent;

            if (activeCard) activeCard.classList.remove('hs-tc-active');
            activeCard = card;
            card.classList.add('hs-tc-active');

            overlay.classList.add('hs-tc-open');
            closeBtn.focus();
            paused = true;
            overlay.addEventListener('keydown', trapFocus);
        }

        function closePopup() {
            overlay.classList.remove('hs-tc-open');
            if (activeCard) { activeCard.classList.remove('hs-tc-active'); activeCard = null; }
            paused = false;
            overlay.removeEventListener('keydown', trapFocus);
        }

        function trapFocus(e) {
            if (e.key === 'Escape') { closePopup(); return; }
            if (e.key !== 'Tab') return;
            var focusable = overlay.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            var first = focusable[0], last = focusable[focusable.length - 1];
            if (e.shiftKey) { if (document.activeElement === first) { e.preventDefault(); last.focus(); } }
            else            { if (document.activeElement === last)  { e.preventDefault(); first.focus(); } }
        }

        closeBtn.addEventListener('click', closePopup);
        /* Use wrap so clicking the close btn (outside modal) doesn't double-fire */
        overlay.addEventListener('click', function (e) { if (!wrap.contains(e.target)) closePopup(); });

        /* ── Event delegation on each COLUMN container ──
           Works on both original cards AND clones, regardless of scroll position.
           The key insight: col2 starts at -half2 so CLONES are often visible first;
           per-card listeners on originals silently fail on clones. ── */
        function attachClickDelegation(colEl) {
            /* Keyboard accessibility: mark all cards (originals + clones) */
            colEl.querySelectorAll('.hs-team-card').forEach(function (card) {
                card.setAttribute('tabindex', card.getAttribute('aria-hidden') ? '-1' : '0');
                card.setAttribute('role', 'button');
            });

            /* Single delegated click on the column wrapper */
            colEl.addEventListener('click', function (e) {
                var card = e.target.closest ? e.target.closest('.hs-team-card') : null;
                if (!card) return;
                /* Read data from the clicked card directly — cloneNode(true) copies all data-* attrs */
                openPopup(card);
            });

            /* Keyboard: keydown on column, target must be a card */
            colEl.addEventListener('keydown', function (e) {
                if (e.key !== 'Enter' && e.key !== ' ') return;
                var card = e.target.closest ? e.target.closest('.hs-team-card') : null;
                if (!card) return;
                e.preventDefault();
                openPopup(card);
            });
        }

        attachClickDelegation(col1El);
        if (col2El) attachClickDelegation(col2El);
    }

    global.hsInitTeamCarousel = hsInitTeamCarousel;

}(window));
