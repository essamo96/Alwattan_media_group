<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model {

    use SoftDeletes;

    protected $table = 'courses';
    protected $fillable = [
        'name', 'start_date', 'end_date', 'days_of_week', 'status', 'notes',
    ];
    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    //////////////////////////////////////////////
    public static function daysOfWeekLabels() {
        return [
            'sun' => 'الأحد',
            'mon' => 'الاثنين',
            'tue' => 'الثلاثاء',
            'wed' => 'الأربعاء',
            'thu' => 'الخميس',
            'fri' => 'الجمعة',
            'sat' => 'السبت',
        ];
    }

    //////////////////////////////////////////////
    public function daysOfWeekLabel() {
        $labels = self::daysOfWeekLabels();
        return collect($this->days_of_week ?? [])
            ->map(fn($day) => $labels[$day] ?? $day)
            ->implode('، ');
    }

    //////////////////////////////////////////////
    public function candidates() {
        return $this->hasMany(CourseCandidate::class);
    }

    //////////////////////////////////////////////
    public function addCourse($data) {
        return $this->create($data);
    }

    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this->where('id', '=', $id)->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    public function deleteCourse($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getCourse($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    public function getSearchCourses($name = '') {
        return $this->where(function ($query) use ($name) {
                    if ($name != '') {
                        $query->where('name', 'LIKE', '%' . $name . '%');
                    }
                })
                ->orderBy('id', 'desc');
    }

}
