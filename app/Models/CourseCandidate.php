<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCandidate extends Model {

    use SoftDeletes;

    protected $table = 'course_candidates';
    protected $fillable = [
        'course_id', 'course_registration_id',
    ];

    //////////////////////////////////////////////
    public function course() {
        return $this->belongsTo(Course::class);
    }

    //////////////////////////////////////////////
    public function registration() {
        return $this->belongsTo(CourseRegistration::class, 'course_registration_id');
    }

    /**
     * يضيف مرشحاً لدورة، أو يعيد تفعيله ان كان محذوفاً سابقاً منها (soft delete).
     * آمن يُستدعى عدة مرات لنفس الدورة بفلاتر/دفعات مختلفة بدون تكرار.
     *
     * @return bool  true اذا أُضيف/أُعيد تفعيله فعلياً الآن، false اذا كان مرشحاً فعالاً أصلاً
     */
    public static function addCandidate($course_id, $course_registration_id): bool {
        // withTrashed حتى لو كان هذا المرشح أُزيل سابقاً من نفس الدورة (soft delete)
        // نعيد تفعيله بدل محاولة إدراج صف جديد يصطدم بالـ unique index.
        $existing = self::withTrashed()
            ->where('course_id', $course_id)
            ->where('course_registration_id', $course_registration_id)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                return true;
            }
            return false;
        }

        self::create([
            'course_id' => $course_id,
            'course_registration_id' => $course_registration_id,
        ]);
        return true;
    }

}
