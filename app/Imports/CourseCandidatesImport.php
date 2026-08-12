<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

/**
 * يقرأ ملف اكسل (أي تنسيق: مصدره لا يهم) ويستخرج صف عناوين + صفوف بيانات، ثم يحدد
 * أعمدة "الاسم" و"الجوال" و"البريد الإلكتروني" تلقائياً من نص العنوان (عربي/إنجليزي)
 * بدل الاعتماد على ترتيب أعمدة ثابت — أي ملف Excel مُصدَّر أو مُعَدّ يدوياً يشتغل طالما
 * فيه عمود يحمل تسمية معروفة.
 */
class CourseCandidatesImport implements ToCollection {

    public array $rows = [];

    private const NAME_HEADERS = ['name', 'full_name', 'الاسم', 'الاسم رباعي', 'اسم المتقدم', 'اسم المرشح'];
    private const MOBILE_HEADERS = ['mobile', 'phone', 'رقم الجوال', 'الجوال', 'رقم الهاتف', 'الهاتف'];
    private const EMAIL_HEADERS = ['email', 'mail', 'البريد الإلكتروني', 'البريد الالكتروني', 'الايميل', 'الايميل الإلكتروني'];

    public function collection(Collection $sheetRows) {
        if ($sheetRows->isEmpty()) {
            return;
        }

        $header = $sheetRows->first()->map(fn($v) => trim((string) $v));
        $nameCol = $this->findColumn($header, self::NAME_HEADERS);
        $mobileCol = $this->findColumn($header, self::MOBILE_HEADERS);
        $emailCol = $this->findColumn($header, self::EMAIL_HEADERS);

        foreach ($sheetRows->skip(1) as $row) {
            $mobile = $mobileCol !== null ? trim((string) $row->get($mobileCol)) : '';
            $email = $emailCol !== null ? trim((string) $row->get($emailCol)) : '';
            $name = $nameCol !== null ? trim((string) $row->get($nameCol)) : '';

            if ($mobile === '' && $email === '' && $name === '') {
                continue; // صف فارغ بالكامل، تجاهله
            }

            $this->rows[] = [
                'name' => $name,
                'mobile' => $mobile,
                'email' => $email,
            ];
        }
    }

    private function findColumn(Collection $header, array $candidates): ?int {
        foreach ($candidates as $candidate) {
            $index = $header->search(function ($value) use ($candidate) {
                return mb_strtolower(trim($value)) === mb_strtolower($candidate);
            });
            if ($index !== false) {
                return $index;
            }
        }
        return null;
    }

}
