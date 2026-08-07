/*
 * Collapses overflowing #mainmenu items into a "More" dropdown so the
 * navbar never wraps to a second line (English labels are wider than
 * Arabic ones and used to push the menu onto two rows).
 *
 * Only applies to the desktop horizontal navbar (style.css switches
 * #mainmenu to a full-width vertical drawer below 992px, where every
 * item intentionally sits on its own row - that is not overflow).
 */
(function () {
    var MOBILE_BREAKPOINT = 992;

    function initPriorityNav() {
        var menu = document.getElementById('mainmenu');
        var moreItem = document.getElementById('menu-more-item');
        var moreList = document.getElementById('menu-more-list');
        if (!menu || !moreItem || !moreList) return;

        var items = Array.prototype.slice.call(menu.querySelectorAll(':scope > li.mainmenu-item'));
        items.forEach(function (li, i) {
            li.dataset.order = i;
        });

        function restore() {
            var moved = Array.prototype.slice.call(moreList.children);
            moved.forEach(function (li) {
                menu.insertBefore(li, moreItem);
            });

            var all = Array.prototype.slice.call(menu.querySelectorAll(':scope > li.mainmenu-item'));
            all.sort(function (a, b) {
                return Number(a.dataset.order) - Number(b.dataset.order);
            });
            all.forEach(function (li) {
                menu.insertBefore(li, moreItem);
            });

            moreItem.style.display = 'none';
        }

        function fits() {
            // Check every visible top-level <li> (menu items, the "More" button when
            // shown, and trailing items like the language switcher) against the same
            // baseline row - not just .mainmenu-item, otherwise once every item has
            // been collapsed into "More" this used to report "fits" unconditionally
            // even though "More" (or the language switcher after it) still wrapped.
            var children = Array.prototype.slice.call(menu.children).filter(function (li) {
                return li.style.display !== 'none';
            });
            if (children.length === 0) return true;
            var top0 = children[0].offsetTop;
            return children.every(function (li) {
                return li.offsetTop <= top0 + 2;
            });
        }

        function adjust() {
            restore();

            // Below the mobile breakpoint #mainmenu becomes a full-width vertical
            // drawer (each item on its own row by design) - never collapse there.
            if (window.innerWidth <= MOBILE_BREAKPOINT) return;

            if (fits()) return;

            moreItem.style.display = '';

            var guard = 0;
            while (!fits() && guard < 50) {
                var all = Array.prototype.slice.call(menu.querySelectorAll(':scope > li.mainmenu-item'));
                if (all.length === 0) break;
                var last = all[all.length - 1];
                moreList.insertBefore(last, moreList.firstChild);
                guard++;
            }
        }

        var resizeTimer;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(adjust, 150);
        });
        window.addEventListener('load', adjust);
        adjust();

        // custom web fonts (Almarai) swap in after the initial paint and can
        // widen the menu text enough to cause a wrap that the checks above,
        // running before the font is ready, never saw
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(adjust);
        }
        setTimeout(adjust, 500);
        setTimeout(adjust, 1500);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPriorityNav);
    } else {
        initPriorityNav();
    }
})();
