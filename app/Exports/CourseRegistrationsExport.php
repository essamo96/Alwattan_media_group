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

    public function __construct($filters = []) {
        $this->filters = $filters;
    }

    public function collection() {
        $registration = new CourseRegistration();
        return $registration->applyFilters($this->filters)->orderBy('id', 'desc')->get();
    }

    public function headings(): array {
        return [
            '#', 'الاسم رباعي', 'رقم الهوية', 'الجنس', 'تاريخ الميلاد', 'التخصص العام',
            'التخصص الدقيق', 'سنة التخرج', 'الجامعة', 'المعدل', 'الجنسية', 'العنوان الحالي',
            'مكان الميلاد', 'جهة العمل', 'الحالة الاجتماعية', 'رقم الجوال', 'البريد الإلكتروني',
            'إعاقة صحية', 'وصف الإعاقة', 'تاريخ التسجيل',
        ];
    }

    public function map($row): array {
        return [
            $row->id,
            $row->full_name,
            $row->national_id,
            $row->genderLabel(),
            optional($row->birth_date)->format('Y-m-d'),
            $row->general_specialization,
            $row->specific_specialization,
            $row->graduation_year,
            $row->university,
            $row->gpa,
            $row->nationality,
            $row->current_address,
            $row->birth_place,
            $row->employer,
            $row->maritalStatusLabel(),
            $row->mobile,
            $row->email,
            $row->has_disability ? 'نعم' : 'لا',
            $row->disability_description,
            optional($row->created_at)->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet) {
        $sheet->getStyle('A1:T1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:T1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:T1')->getFill()
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
