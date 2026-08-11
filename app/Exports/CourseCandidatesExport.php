<?php

namespace App\Exports;

use App\Models\CourseCandidate;
use App\Models\CourseRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseCandidatesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents {

    protected $filters;
    protected $courseId;
    protected $columns;
    protected $rowNumber = 0;

    /**
     * سجل كل الأعمدة المتاحة للتصدير: مفتاح => [تسمية عربية، دالة تُرجع القيمة لكل صف (CourseCandidate مع registration/course محملين).
     * الترتيب هنا هو الترتيب الافتراضي عند عدم تحديد أعمدة صراحةً.
     */
    public static function availableColumns(): array {
        return [
            'id' => ['label' => '#', 'value' => fn($row) => $row->id],
            'course_name' => ['label' => 'الدورة', 'value' => fn($row) => $row->course->name ?? ''],
            'full_name' => ['label' => 'الاسم رباعي', 'value' => fn($row) => $row->registration->full_name ?? ''],
            'national_id' => ['label' => 'رقم الهوية', 'value' => fn($row) => $row->registration->national_id ?? ''],
            'gender' => ['label' => 'الجنس', 'value' => fn($row) => $row->registration ? $row->registration->genderLabel() : ''],
            'age' => ['label' => 'العمر', 'value' => fn($row) => $row->registration->age ?? ''],
            'general_specialization' => ['label' => 'التخصص العام', 'value' => fn($row) => $row->registration->general_specialization ?? ''],
            'specific_specialization' => ['label' => 'التخصص الدقيق', 'value' => fn($row) => $row->registration->specific_specialization ?? ''],
            'graduation_year' => ['label' => 'سنة التخرج', 'value' => fn($row) => $row->registration->graduation_year ?? ''],
            'university' => ['label' => 'الجامعة', 'value' => fn($row) => $row->registration->university ?? ''],
            'gpa' => ['label' => 'المعدل', 'value' => fn($row) => $row->registration->gpa ?? ''],
            'nationality' => ['label' => 'الجنسية', 'value' => fn($row) => $row->registration->nationality ?? ''],
            'citizen_type' => ['label' => 'نوع المسجل', 'value' => fn($row) => $row->registration ? $row->registration->citizenTypeLabel() : ''],
            'unrwa_card_number' => ['label' => 'رقم تسجيل بطاقة الوكالة', 'value' => fn($row) => $row->registration->unrwa_card_number ?? ''],
            'employer' => ['label' => 'جهة العمل', 'value' => fn($row) => $row->registration->employer ?? ''],
            'marital_status' => ['label' => 'الحالة الاجتماعية', 'value' => fn($row) => $row->registration ? $row->registration->maritalStatusLabel() : ''],
            'mobile' => ['label' => 'رقم الجوال', 'value' => fn($row) => $row->registration->mobile ?? ''],
            'email' => ['label' => 'البريد الإلكتروني', 'value' => fn($row) => $row->registration->email ?? ''],
            'created_at' => ['label' => 'تاريخ الترشيح', 'value' => fn($row) => optional($row->created_at)->format('Y-m-d H:i')],
        ];
    }

    /**
     * @param  array  $filters  فلاتر applyFilters على CourseRegistration
     * @param  int|null  $courseId  حصر النتائج بدورة محددة، أو null لكل الدورات
     * @param  array|null  $columns  مفاتيح الأعمدة المطلوبة بالترتيب المطلوب (null = كل الأعمدة بالترتيب الافتراضي)
     */
    public function __construct($filters = [], $courseId = null, ?array $columns = null) {
        $this->filters = $filters;
        $this->courseId = $courseId;

        $available = self::availableColumns();
        $requested = !empty($columns) ? array_values(array_intersect($columns, array_keys($available))) : array_keys($available);
        $this->columns = !empty($requested) ? $requested : array_keys($available);
    }

    public function collection() {
        $hasFilters = count(array_filter($this->filters, fn($v) => !empty($v))) > 0;
        $matchingRegistrationIds = $hasFilters
            ? (new CourseRegistration())->applyFilters($this->filters)->pluck('id')
            : null;

        // الجنس محفوظ بجدول registration المرتبط، فيصعب فرزه بـ orderBy SQL بسيط هون - نفرز
        // النتائج بعد جلبها بالذاكرة: النساء أولاً ثم الرجال، وداخل كل مجموعة الأقدم ترشيحاً أولاً.
        return CourseCandidate::query()
            ->with(['course', 'registration'])
            ->whereHas('registration')
            ->when($this->courseId, function ($q) {
                $q->where('course_id', $this->courseId);
            })
            ->when($matchingRegistrationIds !== null, function ($q) use ($matchingRegistrationIds) {
                $q->whereIn('course_registration_id', $matchingRegistrationIds);
            })
            ->orderBy('id', 'asc')
            ->get()
            ->sortByDesc(fn($row) => $row->registration && $row->registration->gender === 'female')
            ->values();
    }

    public function headings(): array {
        $available = self::availableColumns();
        return array_map(fn($key) => $available[$key]['label'], $this->columns);
    }

    public function map($row): array {
        $this->rowNumber++;
        $available = self::availableColumns();
        return array_map(function ($key) use ($row) {
            // عمود "#" يعرض ترقيماً تسلسلياً 1..N بترتيب التصدير النهائي بدل رقم السجل بقاعدة البيانات.
            if ($key === 'id') {
                return $this->rowNumber;
            }
            return $available[$key]['value']($row);
        }, $this->columns);
    }

    public function styles(Worksheet $sheet) {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($this->columns));
        $range = 'A1:' . $lastCol . '1';
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($range)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('D9E1F2');

        return [];
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
                $event->sheet->getDelegate()->freezePane('A2');
            },
        ];
    }

}
