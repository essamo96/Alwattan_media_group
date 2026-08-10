<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * تكامل مع خدمة tweetsms.ps لإرسال رسائل SMS فورية (HTTP API - comm=sendsms).
 * التوثيق: user/pass/to/message/sender كـ query params، والرد نص خام يبدأ بـ
 * "Result:1:<sms_id>" عند النجاح، أو رمز خطأ سالب (-100, -110, -113, -115, -116...) عند الفشل.
 */
class TweetSmsService {

    protected string $url;
    protected ?string $user;
    protected ?string $pass;
    protected string $sender;

    protected const ERROR_MESSAGES = [
        '-2' => 'وجهة غير صالحة أو دولة غير مدعومة',
        '-999' => 'فشل الإرسال عبر مزود الخدمة',
        '-100' => 'بيانات ناقصة بالطلب (user/pass/to/message/sender)',
        '-110' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
        '-113' => 'الرصيد غير كافٍ لإتمام الإرسال',
        '-115' => 'اسم المرسل غير متاح لهذا الحساب',
        '-116' => 'اسم المرسل غير صالح',
    ];

    public function __construct() {
        $this->url = config('services.tweetsms.url');
        $this->user = config('services.tweetsms.user');
        $this->pass = config('services.tweetsms.pass');
        $this->sender = config('services.tweetsms.sender');
    }

    /**
     * @return array{success:bool, message:string, raw:?string}
     */
    public function send(?string $to, string $message): array {
        if (empty($this->user) || empty($this->pass)) {
            return ['success' => false, 'message' => 'إعدادات خدمة الرسائل غير مكتملة (user/pass)', 'raw' => null];
        }

        $normalized = $this->normalizeMobile($to);
        if (!$normalized) {
            return ['success' => false, 'message' => 'رقم جوال غير صالح', 'raw' => null];
        }

        try {
            $response = Http::timeout(15)->get($this->url, [
                'comm' => 'sendsms',
                'user' => $this->user,
                'pass' => $this->pass,
                'to' => $normalized,
                'message' => $message,
                'sender' => $this->sender,
            ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'تعذر الاتصال بخدمة الرسائل: ' . $e->getMessage(), 'raw' => null];
        }

        return $this->parseResponse(trim((string) $response->body()));
    }

    /**
     * يحوّل رقم فلسطيني محلي (05XXXXXXXX) لصيغة دولية (9725XXXXXXXX) المطلوبة من tweetsms.
     */
    protected function normalizeMobile(?string $mobile): ?string {
        if (!$mobile) {
            return null;
        }
        $digits = preg_replace('/\D/', '', $mobile);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '972')) {
            return $digits;
        }
        if (str_starts_with($digits, '0')) {
            return '972' . substr($digits, 1);
        }
        if (str_starts_with($digits, '5') && strlen($digits) === 9) {
            return '972' . $digits;
        }
        return $digits;
    }

    protected function parseResponse(string $body): array {
        if ($body === '') {
            return ['success' => false, 'message' => 'رد فارغ من مزود الرسائل', 'raw' => $body];
        }

        if (preg_match('/^Result:1\b/i', $body) || preg_match('/^1:/', $body)) {
            return ['success' => true, 'message' => 'تم الإرسال بنجاح', 'raw' => $body];
        }

        foreach (self::ERROR_MESSAGES as $code => $label) {
            if (str_contains($body, $code)) {
                return ['success' => false, 'message' => $label, 'raw' => $body];
            }
        }

        return ['success' => false, 'message' => 'استجابة غير معروفة من مزود الرسائل: ' . $body, 'raw' => $body];
    }

}
