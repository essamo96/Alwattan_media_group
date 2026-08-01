/*
 * Collapses overflowing #mainmenu items into a "More" dropdown so the
 * navbar never wraps to a second line (English labels are wider than
 * Arabic ones and used to push the menu onto two rows).
 */
(function () {
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
            var all = Array.prototype.slice.call(menu.querySelectorAll(':scope > li.mainmenu-item'));
            if (all.length === 0) return true;
            var top0 = all[0].offsetTop;
            var wrapped = all.some(function (li) {
                return li.offsetTop > top0 + 2;
            });
            var moreWrapped = moreItem.style.display !== 'none' && moreItem.offsetTop > top0 + 2;
            return !wrapped && !moreWrapped;
        }

        function adjust() {
            restore();
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
