/*
 * سلوك القوائم المنسدلة في #mainmenu (انظر menu-dropdown.css):
 *  - سطح المكتب: تفتح بالتمرير (CSS) وأيضاً بالنقر؛ Esc يغلقها، والأسهم تنقّل بين عناصرها.
 *  - اللمس/الجوال: النقر على العنصر الأب يفتح/يطوي القائمة (accordion) بدل الاعتماد على hover.
 *  - تُغلق عند النقر خارجها أو خروج التركيز منها.
 *  - تُقلَّب جهتها تلقائياً إن كانت ستخرج من الشاشة.
 * يعمل مع nav-priority.js الذي ينقل عناصر لقائمة «المزيد»: الاستماع مفوَّض على #mainmenu
 * والعناصر المنقولة تحتفظ بمستمعيها لأن العقد نفسها تُنقل.
 */
(function () {
    'use strict';

    var MOBILE_BREAKPOINT = 992;
    var menu = document.getElementById('mainmenu');
    if (!menu) return;

    var canHover = !!(window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches);

    function isMobile() {
        return window.innerWidth <= MOBILE_BREAKPOINT;
    }
    function subList(li) {
        return li.querySelector(':scope > ul');
    }
    function parentLink(li) {
        return li.querySelector(':scope > a');
    }
    function isParent(li) {
        return li && li.tagName === 'LI' && li.classList.contains('has-children') && !!subList(li);
    }

    // هل القائمة الفرعية ظاهرة الآن؟ نعتمد على الحالة (is-open أو التمرير) لا على opacity المحسوبة،
    // كي لا تُفهم القائمة «مخفية» أثناء حركة الظهور (0.2ث) فيُعاد فتحها بدل إغلاقها عند نقرتين سريعتين.
    function isShown(li) {
        if (li.classList.contains('is-open')) return true;
        return canHover && !isMobile() && !li.classList.contains('is-closed') &&
            !li.closest('#menu-more-list') && li.matches(':hover');
    }

    function setOpen(li, open) {
        li.classList.toggle('is-open', open);
        var a = parentLink(li);
        if (a) a.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            li.classList.remove('is-closed');
            position(li);
        }
    }

    function closeAll(except) {
        Array.prototype.forEach.call(menu.querySelectorAll('li.has-children.is-open'), function (li) {
            // أبقِ الآباء (الذين يحتوون العنصر المستثنى) مفتوحين
            if (except && (li === except || li.contains(except))) return;
            setOpen(li, false);
        });
    }

    function openOnly(li) {
        closeAll(li);
        setOpen(li, true);
    }

    // إن كانت اللوحة ستخرج من الشاشة نقلبها لجهة الحافة المقابلة
    function position(li) {
        var ul = subList(li);
        if (!ul || isMobile() || li.closest('#menu-more-list')) return;
        li.classList.remove('flip');
        var r = ul.getBoundingClientRect();
        if (r.left < 8 || r.right > window.innerWidth - 8) {
            li.classList.add('flip');
        }
    }

    // ---- تهيئة: خصائص الوصولية لكل عنصر أب ----
    function enhance() {
        Array.prototype.forEach.call(menu.querySelectorAll('li.has-children'), function (li) {
            var a = parentLink(li);
            if (!a || !subList(li)) return;
            a.setAttribute('aria-haspopup', 'true');
            if (!a.hasAttribute('aria-expanded')) a.setAttribute('aria-expanded', 'false');
        });
    }
    enhance();

    // ---- تمرير الماوس: نضبط الجهة قبل الظهور، ونُلغي حالة «أُغلقت بالنقر» ----
    Array.prototype.forEach.call(menu.querySelectorAll('li.has-children'), function (li) {
        li.addEventListener('pointerenter', function (e) {
            if (e.pointerType && e.pointerType !== 'mouse') return;
            li.classList.remove('is-closed');
            position(li);
        });
        li.addEventListener('pointerleave', function (e) {
            if (e.pointerType && e.pointerType !== 'mouse') return;
            li.classList.remove('is-closed');
        });
    });

    // ---- النقر على العنصر الأب ----
    menu.addEventListener('click', function (e) {
        var a = e.target.closest('a');
        if (!a || !menu.contains(a)) return;
        var li = a.parentElement;
        if (!isParent(li) || parentLink(li) !== a) return;

        var href = a.getAttribute('href') || '';
        var isVoid = href === '' || href === '#' || href.indexOf('javascript:') === 0;
        var touchLike = isMobile() || !canHover;

        // رابط حقيقي على سطح المكتب: ينتقل مباشرة (القائمة تفتح بالتمرير)
        if (!isVoid && !touchLike) return;

        var shown = isShown(li);

        // رابط حقيقي على اللمس: أول نقرة تفتح القائمة، الثانية تنتقل للرابط
        if (!isVoid) {
            if (shown) return;
            e.preventDefault();
            openOnly(li);
            return;
        }

        e.preventDefault();
        if (shown) {
            setOpen(li, false);
            // الماوس ما زال فوق العنصر: نمنع التمرير من إعادة فتحها حتى يغادر
            if (!touchLike) li.classList.add('is-closed');
        } else {
            openOnly(li);
        }
    });

    // ---- لوحة المفاتيح ----
    menu.addEventListener('keydown', function (e) {
        var key = e.key;

        if (key === 'Escape') {
            var host = e.target.closest ? e.target.closest('li.has-children') : null;
            closeAll();
            Array.prototype.forEach.call(menu.querySelectorAll('li.has-children'), function (li) {
                if (li.matches(':hover')) li.classList.add('is-closed');
            });
            if (host) {
                var pa = parentLink(host);
                if (pa && document.activeElement !== pa && host.contains(document.activeElement)) pa.focus();
            }
            return;
        }

        if (key !== 'ArrowDown' && key !== 'ArrowUp') return;
        var a = e.target.closest('a');
        if (!a) return;
        var li = a.parentElement;

        // السهم لأسفل على العنصر الأب: افتح وانتقل لأول عنصر
        if (isParent(li) && parentLink(li) === a && key === 'ArrowDown') {
            e.preventDefault();
            openOnly(li);
            var first = subList(li).querySelector('a');
            if (first) first.focus();
            return;
        }

        // التنقل داخل اللوحة
        var ul = a.closest('ul');
        if (!ul || ul === menu) return;
        var items = Array.prototype.filter.call(ul.querySelectorAll(':scope > li > a'), function (x) {
            return x.offsetParent !== null;
        });
        var i = items.indexOf(a);
        if (i === -1) return;
        e.preventDefault();
        var next = key === 'ArrowDown' ? items[(i + 1) % items.length] : items[(i - 1 + items.length) % items.length];
        if (next) next.focus();
    });

    // ---- إغلاق عند خروج التركيز من القائمة ----
    menu.addEventListener('focusout', function (e) {
        var li = e.target.closest ? e.target.closest('li.has-children') : null;
        if (!li) return;
        setTimeout(function () {
            if (li.contains(document.activeElement)) return;
            if (canHover && !isMobile() && li.matches(':hover')) return;
            setOpen(li, false);
        }, 0);
    });

    // ---- إغلاق عند النقر خارج القائمة ----
    document.addEventListener('click', function (e) {
        if (!menu.contains(e.target)) closeAll();
    });

    // ---- أزرار فتح/إغلاق درج الجوال ----
    ['menu-btn', 'b-menu'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function () { closeAll(); });
    });

    // ---- تغيّر الحجم (عبور حد الجوال) ----
    var lastMobile = isMobile();
    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (isMobile() !== lastMobile) {
                lastMobile = isMobile();
                closeAll();
            }
        }, 150);
    });
})();
