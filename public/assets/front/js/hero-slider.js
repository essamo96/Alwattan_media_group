/* ==========================================================================
   سلايدر صور القسم الرئيسي — ظهور واختفاء تدريجي (fade)
   الصور تُقرأ من العنصر ذو الكلاس .hero-slideshow عبر الخاصية data-images
   (مصدرها المجلد: public/assets/front/images/slider)
   ========================================================================== */
(function () {
    'use strict';

    var DEFAULT_INTERVAL = 6000; // مدة بقاء كل صورة بالمللي ثانية

    function initSlideshow(container) {
        var images;
        try {
            images = JSON.parse(container.getAttribute('data-images') || '[]');
        } catch (e) {
            images = [];
        }

        if (!images.length) {
            return;
        }

        var interval = parseInt(container.getAttribute('data-interval'), 10) || DEFAULT_INTERVAL;
        var slides = [];

        // بناء عناصر الصور
        images.forEach(function (src, index) {
            var img = document.createElement('img');
            img.className = 'hero-slideshow__img';
            img.src = src;
            img.alt = '';
            img.setAttribute('aria-hidden', 'true');
            if (index === 0) {
                img.classList.add('is-active');
            } else {
                img.loading = 'lazy';
            }
            container.appendChild(img);
            slides.push(img);
        });

        // صورة واحدة فقط: لا حاجة للتبديل
        if (slides.length < 2) {
            return;
        }

        var current = 0;
        var timer = null;

        function showNext() {
            var next = (current + 1) % slides.length;
            slides[current].classList.remove('is-active');
            slides[next].classList.add('is-active');
            current = next;
        }

        function start() {
            if (timer === null) {
                timer = window.setInterval(showNext, interval);
            }
        }

        function stop() {
            if (timer !== null) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        // ايقاف التبديل عندما يكون التبويب غير ظاهر
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stop();
            } else {
                start();
            }
        });

        start();
    }

    function init() {
        var containers = document.querySelectorAll('.hero-slideshow');
        for (var i = 0; i < containers.length; i++) {
            initSlideshow(containers[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
