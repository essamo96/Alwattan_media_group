{{-- نافذة إرسال SMS موحّدة، تُستدعى من أي شاشة عبر window.SmsCompose.open({...}) --}}
<div class="modal fade" id="sms_compose_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sms_compose_title">إرسال رسالة SMS</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sms_compose_recipient_info" class="alert alert-light-primary d-none mb-4"></div>

                <label class="form-label">نص الرسالة</label>
                <textarea id="sms_compose_message" class="form-control" rows="5" maxlength="900" placeholder="اكتب نص الرسالة هنا..."></textarea>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <span class="text-muted fs-8">أدرج متغير:</span>
                        <button type="button" class="btn btn-sm btn-light-primary sms-var-btn" data-var="{name}">اسم المسجل</button>
                        <button type="button" class="btn btn-sm btn-light-primary sms-var-btn" data-var="{email}">البريد</button>
                        <button type="button" class="btn btn-sm btn-light-primary sms-var-btn" data-var="{mobile}">الجوال</button>
                    </div>
                    <span class="text-muted fs-8"><span id="sms_compose_count">0</span>/900</span>
                </div>
                <div class="text-muted fs-7 mt-2">المتغيرات تُستبدل تلقائياً ببيانات كل مستلم عند الإرسال.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="sms_compose_send_btn" class="btn btn-primary">
                    <i class="ki-duotone ki-message-text-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <span id="sms_compose_send_label">إرسال</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ملاحظة: تحميل sms-compose.js هون بيفشل — هاي الجزئية (@yield('modals')) بترندر قبل jQuery
     بلاياوت layouts/admin.blade.php. لازم يُحمّل من @push('scripts') بكل صفحة تستخدم هالمودال. --}}
