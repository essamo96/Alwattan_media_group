<?php

namespace App\Exports;

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

class CourseRegistrationsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents {

    protected $filters;
    protected $columns;

    /**
     * سجل كل الأعمدة المتاحة للتصدير: مفتاح => [تسمية عربية، دالة تُرجع القيمة لكل صف].
     * الترتيب هنا هو الترتيب الافتراضي عند عدم تحديد أعمدة صراحةً.
     */
    public static function availableColumns(): array {
        return [
            'id' => ['label' => '#', 'value' => fn($row) => $row->id],
            'full_name' => ['label' => 'الاسم رباعي', 'value' => fn($row) => $row->full_name],
            'national_id' => ['label' => 'رقم الهوية', 'value' => fn($row) => $row->national_id],
            'gender' => ['label' => 'الجنس', 'value' => fn($row) => $row->genderLabel()],
            'birth_date' => ['label' => 'تاريخ الميلاد', 'value' => fn($row) => optional($row->birth_date)->format('Y-m-d')],
            'age' => ['label' => 'العمر', 'value' => fn($row) => $row->age],
            'general_specialization' => ['label' => 'التخصص العام', 'value' => fn($row) => $row->general_specialization],
            'specific_specialization' => ['label' => 'التخصص الدقيق', 'value' => fn($row) => $row->specific_specialization],
            'graduation_year' => ['label' => 'سنة التخرج', 'value' => fn($row) => $row->graduation_year],
            'university' => ['label' => 'الجامعة', 'value' => fn($row) => $row->university],
            'gpa' => ['label' => 'المعدل', 'value' => fn($row) => $row->gpa],
            'nationality' => ['label' => 'الجنسية', 'value' => fn($row) => $row->nationality],
            'citizen_type' => ['label' => 'نوع المسجل', 'value' => fn($row) => $row->citizenTypeLabel()],
            'unrwa_card_number' => ['label' => 'رقم تسجيل بطاقة الوكالة', 'value' => fn($row) => $row->unrwa_card_number],
            'current_address' => ['label' => 'العنوان الحالي', 'value' => fn($row) => $row->current_address],
            'birth_place' => ['label' => 'مكان الميلاد', 'value' => fn($row) => $row->birth_place],
            'employer' => ['label' => 'جهة العمل', 'value' => fn($row) => $row->employer],
            'marital_status' => ['label' => 'الحالة الاجتماعية', 'value' => fn($row) => $row->maritalStatusLabel()],
            'mobile' => ['label' => 'رقم الجوال', 'value' => fn($row) => $row->mobile],
            'email' => ['label' => 'البريد الإلكتروني', 'value' => fn($row) => $row->email],
            'has_disability' => ['label' => 'إعاقة صحية', 'value' => fn($row) => $row->has_disability ? 'نعم' : 'لا'],
            'disability_description' => ['label' => 'وصف الإعاقة', 'value' => fn($row) => $row->disability_description],
            'created_at' => ['label' => 'تاريخ التسجيل', 'value' => fn($row) => optional($row->created_at)->format('Y-m-d H:i')],
        ];
    }

    /**
     * @param  array  $filters
     * @param  array|null  $columns  مفاتيح الأعمدة المطلوبة بالترتيب المطلوب (null = كل الأعمدة بالترتيب الافتراضي)
     */
    public function __construct($filters = [], ?array $columns = null) {
        $this->filters = $filters;

        $available = self::availableColumns();
        $requested = !empty($columns) ? array_values(array_intersect($columns, array_keys($available))) : array_keys($available);
        // احتياط: لو كل الأعمدة المطلوبة غير صالحة (مثلاً قيم غريبة بالطلب)، ارجع لكل الأعمدة.
        $this->columns = !empty($requested) ? $requested : array_keys($available);
    }

    public function collection() {
        $registration = new CourseRegistration();
        return $registration->applyFilters($this->filters)->orderBy('id', 'desc')->get();
    }

    public function headings(): array {
        $available = self::availableColumns();
        return array_map(fn($key) => $available[$key]['label'], $this->columns);
    }

    public function map($row): array {
        $available = self::availableColumns();
        return array_map(fn($key) => $available[$key]['value']($row), $this->columns);
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
