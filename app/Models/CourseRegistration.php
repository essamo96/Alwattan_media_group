<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRegistration extends Model {

    use SoftDeletes;

    protected $table = 'course_registrations';
    protected $fillable = [
        'full_name', 'national_id', 'gender', 'birth_date', 'general_specialization',
        'specific_specialization', 'graduation_year', 'university', 'gpa', 'nationality',
        'current_address', 'birth_place', 'employer', 'marital_status', 'mobile', 'email',
    ];

    //////////////////////////////////////////////
    public static function genderLabels() {
        return ['male' => 'ذكر', 'female' => 'أنثى'];
    }

    //////////////////////////////////////////////
    public static function maritalStatusLabels() {
        return ['single' => 'أعزب', 'married' => 'متزوج', 'divorced' => 'مطلق', 'widowed' => 'أرمل'];
    }

    //////////////////////////////////////////////
    public function genderLabel() {
        return self::genderLabels()[$this->gender] ?? $this->gender;
    }

    //////////////////////////////////////////////
    public function maritalStatusLabel() {
        return self::maritalStatusLabels()[$this->marital_status] ?? $this->marital_status;
    }

    //////////////////////////////////////////////
    public function addRegistration($data) {
        return $this->create($data);
    }

    //////////////////////////////////////////////
    public function updateStatus($id, $status) {
        return $this->where('id', '=', $id)->update(['status' => $status]);
    }

    //////////////////////////////////////////////
    public function deleteRegistration($obj) {
        return $obj->delete();
    }

    //////////////////////////////////////////////
    public function getRegistration($id) {
        return $this->find($id);
    }

    //////////////////////////////////////////////
    public function searchRegistrations($filters = [], $start = 0, $length = 25) {
        return $this->applyFilters($filters)
                        ->skip($start)
                        ->orderBy('id', 'desc')
                        ->take($length);
    }

    //////////////////////////////////////////////
    public function searchRegistrationsCount($filters = []) {
        return $this->applyFilters($filters)->count('id');
    }

    //////////////////////////////////////////////
    public function applyFilters($filters = []) {
        return $this->where(function ($query) use ($filters) {
                    if (!empty($filters['name'])) {
                        $query->where('full_name', 'LIKE', '%' . $filters['name'] . '%');
                    }
                    if (!empty($filters['national_id'])) {
                        $query->where('national_id', 'LIKE', '%' . $filters['national_id'] . '%');
                    }
                    if (!empty($filters['gender'])) {
                        $query->where('gender', '=', $filters['gender']);
                    }
                    if (!empty($filters['marital_status'])) {
                        $query->where('marital_status', '=', $filters['marital_status']);
                    }
                    if (!empty($filters['nationality'])) {
                        $query->where('nationality', 'LIKE', '%' . $filters['nationality'] . '%');
                    }
                    if (!empty($filters['general_specialization'])) {
                        $query->where('general_specialization', 'LIKE', '%' . $filters['general_specialization'] . '%');
                    }
                    if (!empty($filters['specific_specialization'])) {
                        $query->where('specific_specialization', 'LIKE', '%' . $filters['specific_specialization'] . '%');
                    }
                    if (!empty($filters['graduation_year_from'])) {
                        $query->where('graduation_year', '>=', $filters['graduation_year_from']);
                    }
                    if (!empty($filters['graduation_year_to'])) {
                        $query->where('graduation_year', '<=', $filters['graduation_year_to']);
                    }
                    if (!empty($filters['university'])) {
                        $query->where('university', 'LIKE', '%' . $filters['university'] . '%');
                    }
                    if (!empty($filters['gpa_from'])) {
                        $query->where('gpa', '>=', $filters['gpa_from']);
                    }
                    if (!empty($filters['gpa_to'])) {
                        $query->where('gpa', '<=', $filters['gpa_to']);
                    }
                    if (!empty($filters['employer'])) {
                        $query->where('employer', 'LIKE', '%' . $filters['employer'] . '%');
                    }
                    if (!empty($filters['mobile'])) {
                        $query->where('mobile', 'LIKE', '%' . $filters['mobile'] . '%');
                    }
                    if (!empty($filters['email'])) {
                        $query->where('email', 'LIKE', '%' . $filters['email'] . '%');
                    }
                    if (!empty($filters['date_from'])) {
                        $query->whereDate('created_at', '>=', $filters['date_from']);
                    }
                    if (!empty($filters['date_to'])) {
                        $query->whereDate('created_at', '<=', $filters['date_to']);
                    }
                });
    }

}
