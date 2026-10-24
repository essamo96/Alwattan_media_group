{{-- تقويم اختيار تاريخ ووقت النشر (flatpickr المضمّنة في plugins.bundle.js) — مشترك بين شاشتي الإضافة والتعديل. --}}
<script type="text/javascript">
(function () {
    var wrap = document.getElementById('pub_date_wrap');
    if (!wrap || typeof flatpickr === 'undefined') {
        return; // يبقى الحقل نصياً عادياً (Y-m-d H:i:s) إن لم تُحمَّل المكتبة
    }

    flatpickr(wrap, {
        wrap: true,                 // الزر [data-toggle] يفتح التقويم، والحقل [data-input]
        enableTime: true,
        enableSeconds: true,
        time_24hr: true,
        allowInput: true,
        dateFormat: 'Y-m-d H:i:S',
        locale: (flatpickr.l10ns && flatpickr.l10ns.ar) ? flatpickr.l10ns.ar : 'default',
        disableMobile: true         // نفس التقويم على الجوال بدل منتقي المتصفح
    });
})();
</script>
