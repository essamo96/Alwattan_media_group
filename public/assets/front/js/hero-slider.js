/* ==========================================================================
   سلايدر القسم الرئيسي — يبدّل بين صور uploads/sliders الحقيقية (jarallax-img)
   ونصوصها (hero-text-slide) معاً بنفس الفهرس، بتلاشي متبادل (fade).
   مكتبة jarallax تلتقط أول .jarallax-img فقط وتتجاهل الباقي، لذلك التبديل هنا
   يُدار يدوياً بدل الاعتماد عليها لتعدد الشرائح.
   ========================================================================== */
(function () {
    'use strict';

    var DEFAULT_INTERVAL = 6000; // مدة بقاء كل شريحة بالمللي ثانية

    function init() {
        var imgSlides = document.querySelectorAll('#section-hero .jarallax-img');
        var textSlides = document.querySelectorAll('#section-hero .hero-text-slide');
        var count = Math.max(imgSlides.length, textSlides.length);

        if (count < 2) {
            return;
        }

        var current = 0;

        function show(index) {
            if (imgSlides.length) {
                imgSlides[current] && imgSlides[current].classList.remove('is-active');
                imgSlides[index] && imgSlides[index].classList.add('is-active');
            }
            if (textSlides.length) {
                textSlides[current] && textSlides[current].classList.remove('is-active');
                textSlides[index] && textSlides[index].classList.add('is-active');
            }
        }

        window.setInterval(function () {
            var next = (current + 1) % count;
            show(next);
            current = next;
        }, DEFAULT_INTERVAL);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
