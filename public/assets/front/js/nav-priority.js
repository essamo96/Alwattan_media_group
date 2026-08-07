/*
 * Collapses overflowing #mainmenu items into a "More" dropdown so the
 * navbar never wraps to a second line / drops below the logo (English
 * labels are wider than Arabic ones and used to push the menu down).
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

        function visibleChildren() {
            return Array.prototype.slice.call(menu.children).filter(function (li) {
                return li.style.display !== 'none' && window.getComputedStyle(li).display !== 'none';
            });
        }

        function rowFits() {
            var children = visibleChildren();
            if (children.length === 0) return true;
            var top0 = children[0].offsetTop;
            return children.every(function (li) {
                return li.offsetTop <= top0 + 2;
            });
        }

        function menuDroppedBelowLogo() {
            var logo = document.getElementById('logo');
            if (!logo) return false;
            return menu.offsetTop > logo.offsetTop + 4;
        }

        function exceedsAvailableWidth() {
            var col = menu.parentElement;
            if (!col) return false;
            var logo = document.getElementById('logo');
            var extra = col.querySelector('.header-extra');
            var btn = document.getElementById('menu-btn');
            var used = 0;
            if (logo) used += logo.offsetWidth;
            if (extra && window.getComputedStyle(extra).display !== 'none') {
                used += extra.offsetWidth;
            }
            if (btn && window.getComputedStyle(btn).display !== 'none') {
                used += btn.offsetWidth;
            }
            // هامش أمان بسيط بين الشعار والقائمة
            used += 24;
            var available = col.clientWidth - used;
            if (available <= 0) return true;

            var total = 0;
            visibleChildren().forEach(function (li) {
                total += li.offsetWidth;
            });
            return total > available + 1;
        }

        function fits() {
            return rowFits() && !menuDroppedBelowLogo() && !exceedsAvailableWidth();
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
