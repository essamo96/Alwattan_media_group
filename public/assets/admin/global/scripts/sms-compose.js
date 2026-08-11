/**
 * نافذة إرسال SMS موحّدة (admin.partials.sms-compose-modal).
 * الاستخدام من أي شاشة:
 *   window.SmsCompose.open({
 *       title: 'إرسال SMS لكل النتائج المطابقة',
 *       recipientInfo: 'سيتم الإرسال لكل من يطابق الفلاتر الحالية',
 *       sendUrl: '...',           // route('sms.send')
 *       payload: function () { return {target: 'filtered', name: ..., gender: ...}; },
 *       onDone: function (data) { ... } // اختياري، بعد نجاح الإرسال
 *   });
 */
(function (window, $) {
    'use strict';

    var SmsCompose = {};
    var currentOptions = null;

    SmsCompose.open = function (options) {
        currentOptions = options || {};

        $('#sms_compose_title').text(currentOptions.title || 'إرسال رسالة SMS');
        $('#sms_compose_message').val('');
        $('#sms_compose_count').text('0');

        var $info = $('#sms_compose_recipient_info');
        if (currentOptions.recipientInfo) {
            $info.text(currentOptions.recipientInfo).removeClass('d-none');
        } else {
            $info.addClass('d-none');
        }

        $('#sms_compose_send_label').text(currentOptions.sendLabel || 'إرسال');

        $('#sms_compose_custom_toggle').prop('checked', false);
        $('#sms_compose_custom_mobile').val('');
        $('#sms_compose_custom_field').addClass('d-none');

        $('#sms_compose_modal').modal('show');
    };

    $(function () {
        $(document).on('input', '#sms_compose_message', function () {
            $('#sms_compose_count').text($(this).val().length);
        });

        $(document).on('change', '#sms_compose_custom_toggle', function () {
            $('#sms_compose_custom_field').toggleClass('d-none', !this.checked);
            if (this.checked) {
                $('#sms_compose_custom_mobile').trigger('focus');
            }
        });

        $(document).on('click', '.sms-var-btn', function () {
            var $textarea = $('#sms_compose_message');
            var el = $textarea.get(0);
            var variable = $(this).data('var');
            var start = el.selectionStart || el.value.length;
            var end = el.selectionEnd || el.value.length;
            var value = el.value;
            el.value = value.slice(0, start) + variable + value.slice(end);
            el.selectionStart = el.selectionEnd = start + variable.length;
            el.focus();
            $textarea.trigger('input');
        });

        $(document).on('click', '#sms_compose_send_btn', function () {
            if (!currentOptions) {
                return;
            }
            var message = $('#sms_compose_message').val().trim();
            if (!message) {
                toastr.error('يرجى كتابة نص الرسالة');
                return;
            }

            var useCustom = $('#sms_compose_custom_toggle').is(':checked');
            var payload;

            if (useCustom) {
                var customMobile = $('#sms_compose_custom_mobile').val().trim();
                if (!/^05[0-9]{8}$/.test(customMobile)) {
                    toastr.error('رقم الجوال يجب ان يكون بصيغة فلسطينية صحيحة (05xxxxxxxx)');
                    return;
                }
                payload = {target: 'custom', mobile: customMobile};
            } else {
                payload = typeof currentOptions.payload === 'function' ? currentOptions.payload() : {};
            }
            payload.message = message;

            var $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: currentOptions.sendUrl,
                data: payload
            }).done(function (data) {
                toastr[data.status === 'success' ? 'success' : 'error'](data.message);
                if (data.status === 'success') {
                    $('#sms_compose_modal').modal('hide');
                    if (typeof currentOptions.onDone === 'function') {
                        currentOptions.onDone(data);
                    }
                }
            }).fail(function () {
                toastr.error('تعذر الاتصال بالسيرفر');
            }).always(function () {
                $btn.prop('disabled', false);
            });
        });
    });

    window.SmsCompose = SmsCompose;
})(window, window.jQuery);
