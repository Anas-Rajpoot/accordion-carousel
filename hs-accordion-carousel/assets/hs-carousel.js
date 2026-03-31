/* ============================================================
   HS Accordion Carousel — JavaScript
   Exposes window.hsInitCarousel() — called inline by each
   shortcode instance with its own unique element IDs.
   Desktop: offset-based horizontal accordion with arrow nav.
   Mobile (≤768px): independent tap-to-toggle stacked accordion.
   ============================================================ */

(function (global) {
    'use strict';

    /**
     * Initialise one carousel instance.
     * @param {string} wrapId  — ID of .hs-track-wrap element
     * @param {string} trackId — ID of .hs-track element
     * @param {string} prevId  — ID of the "prev" arrow button
     * @param {string} nextId  — ID of the "next" arrow button
     */
    function hsInitCarousel(wrapId, trackId, prevId, nextId) {
        var track = document.getElementById(trackId);
        if (!track) return;

        var items  = Array.prototype.slice.call(track.querySelectorAll('.hs-item'));
        var offset = 1; /* card 0 starts closed */

        function isMobile() { return window.innerWidth <= 768; }

        /* ── Desktop: apply closed/open classes ── */
        function applyDesktop(animate) {
            track.style.transition = animate
                ? 'transform 0.55s cubic-bezier(0.4,0,0.2,1)'
                : 'none';
            track.style.transform = 'translateX(0)';

            items.forEach(function (item, i) {
                item.classList.toggle('hs-closed', i < offset);
            });

            if (!animate) {
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        track.style.transition = '';
                    });
                });
            }
        }

        /* ── Mobile: reset transform, keep toggle state ── */
        function applyMobile() {
            track.style.transform  = 'none';
            track.style.transition = 'none';
        }

        function apply(animate) {
            if (isMobile()) { applyMobile(); return; }
            applyDesktop(animate);
        }

        /* ── Arrow buttons ── */
        var btnNext = document.getElementById(nextId);
        var btnPrev = document.getElementById(prevId);

        if (btnNext) {
            btnNext.addEventListener('click', function () {
                if (isMobile()) return;
                if (offset < items.length - 1) { offset++; applyDesktop(true); }
            });
        }

        if (btnPrev) {
            btnPrev.addEventListener('click', function () {
                if (isMobile()) return;
                if (offset > 0) { offset--; applyDesktop(true); }
            });
        }

        /* ── Card click ── */
        items.forEach(function (item) {
            item.addEventListener('click', function () {
                if (isMobile()) {
                    /* Mobile: independent toggle */
                    this.classList.toggle('hs-closed');
                    return;
                }
                var idx = parseInt(this.getAttribute('data-index'), 10);
                if (this.classList.contains('hs-closed')) {
                    /* Closed strip → re-open from here */
                    offset = idx;
                    applyDesktop(true);
                } else {
                    /* Open card → collapse it */
                    offset = idx + 1;
                    applyDesktop(true);
                }
            });
        });

        /* ── Resize ── */
        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () { apply(false); }, 120);
        });

        apply(false);
    }

    global.hsInitCarousel = hsInitCarousel;

}(window));
