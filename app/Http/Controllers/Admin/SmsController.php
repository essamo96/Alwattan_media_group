<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\CourseCandidate;
use App\Models\CourseRegistration;
use App\Models\SmsLog;
use App\Services\TweetSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SmsController extends AdminController {

    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const NO_RECIPIENTS = "لا يوجد مستلمون مطابقون لهذا الطلب";

    //////////////////////////////////////////////
    private function filtersFromRequest(Request $request) {
        return [
            'name' => $request->get('name'),
            'national_id' => $request->get('national_id'),
            'gender' => $request->get('gender'),
            'marital_status' => $request->get('marital_status'),
            'nationality' => $request->get('nationality'),
            'general_specialization' => $request->get('general_specialization'),
            'specific_specialization' => $request->get('specific_specialization'),
            'graduation_year_from' => $request->get('graduation_year_from'),
            'graduation_year_to' => $request->get('graduation_year_to'),
            'university' => $request->get('university'),
            'gpa_from' => $request->get('gpa_from'),
            'gpa_to' => $request->get('gpa_to'),
            'employer' => $request->get('employer'),
            'mobile' => $request->get('mobile'),
            'email' => $request->get('email'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'age_from' => $request->get('age_from'),
            'age_to' => $request->get('age_to'),
        ];
    }

    //////////////////////////////////////////////
    // {name} / {email} / {mobile} تُستبدل ببيانات كل مستلم قبل الإرسال.
    private function renderTemplate(string $template, CourseRegistration $registrant): string {
        return strtr($template, [
            '{name}' => $registrant->full_name,
            '{email}' => $registrant->email,
            '{mobile}' => $registrant->mobile,
        ]);
    }

    //////////////////////////////////////////////
    public function postSend(Request $request, TweetSmsService $sms) {
        $request->validate([
            'message' => 'required|string|max:900',
            'target' => 'required|in:single,filtered,course,candidates,custom',
        ]);

        $message = $request->get('message');
        $target = $request->get('target');
        $courseId = null;

        // إرسال لرقم مخصص يكتبه المستخدم يدوياً، متاح من أي شاشة تستخدم نافذة الإرسال الموحّدة،
        // بغض النظر عن الهدف (target) الأصلي المرسل من الشاشة - يتجاوزه بالكامل.
        if ($target === 'custom') {
            $mobile = $request->get('mobile');

            $validator = \Illuminate\Support\Facades\Validator::make(['mobile' => $mobile], [
                'mobile' => ['required', 'regex:/^05[0-9]{8}$/'],
            ], [
                'mobile.regex' => 'رقم الجوال يجب ان يكون بصيغة فلسطينية صحيحة (05xxxxxxxx)',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first('mobile')]);
            }

            $rendered = strtr($message, ['{mobile}' => $mobile, '{name}' => '', '{email}' => '']);
            $result = $sms->send($mobile, $rendered);

            SmsLog::create([
                'recipient_name' => null,
                'recipient_mobile' => $mobile,
                'recipient_email' => null,
                'message' => $rendered,
                'success' => $result['success'],
                'status_message' => $result['message'],
                'provider_response' => $result['raw'],
                'course_id' => null,
                'course_registration_id' => null,
                'sent_by' => Auth::guard('admin')->id(),
            ]);

            return response()->json([
                'status' => $result['success'] ? 'success' : 'error',
                'message' => $result['success'] ? 'تم إرسال الرسالة بنجاح' : ('فشل الإرسال: ' . $result['message']),
                'sent' => $result['success'] ? 1 : 0,
                'failed' => $result['success'] ? 0 : 1,
                'failed_names' => [],
            ]);
        }

        switch ($target) {
            case 'single':
                try {
                    $id = Crypt::decrypt($request->get('registration_id'));
                } catch (DecryptException $e) {
                    return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
                }
                $recipients = CourseRegistration::where('id', $id)->get();
                break;

            case 'course':
                try {
                    $courseId = Crypt::decrypt($request->get('course_id'));
                } catch (DecryptException $e) {
                    return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
                }
                $course = Course::find($courseId);
                if (!$course) {
                    return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
                }
                $recipients = CourseRegistration::whereIn(
                    'id',
                    $course->candidates()->pluck('course_registration_id')
                )->get();
                break;

            case 'candidates':
                // كل المرشحين (بجدول course_candidates) المطابقين للفلاتر الحالية بشاشة "قائمة المرشحين"،
                // اختيارياً محصورين بدورة معينة عبر course_id (قيمة صريحة غير مشفّرة، فلتر مو route id).
                $filters = $this->filtersFromRequest($request);
                $candidatesCourseId = $request->get('course_id');
                $hasFilters = count(array_filter($filters, fn($v) => !empty($v))) > 0;
                $matchingRegistrationIds = $hasFilters
                    ? (new CourseRegistration())->applyFilters($filters)->pluck('id')
                    : null;

                $registrationIds = CourseCandidate::query()
                    ->whereHas('registration')
                    ->when($candidatesCourseId, function ($q) use ($candidatesCourseId) {
                        $q->where('course_id', $candidatesCourseId);
                    })
                    ->when($matchingRegistrationIds !== null, function ($q) use ($matchingRegistrationIds) {
                        $q->whereIn('course_registration_id', $matchingRegistrationIds);
                    })
                    ->pluck('course_registration_id');

                $recipients = CourseRegistration::whereIn('id', $registrationIds)->get();
                break;

            case 'filtered':
            default:
                $filters = $this->filtersFromRequest($request);
                $recipients = (new CourseRegistration())->applyFilters($filters)->get();
                break;
        }

        $recipients = $recipients->filter(fn($r) => !empty($r->mobile));

        if ($recipients->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => self::NO_RECIPIENTS]);
        }

        $sentCount = 0;
        $failedCount = 0;
        $failedNames = [];

        foreach ($recipients as $recipient) {
            $rendered = $this->renderTemplate($message, $recipient);
            $result = $sms->send($recipient->mobile, $rendered);

            SmsLog::create([
                'recipient_name' => $recipient->full_name,
                'recipient_mobile' => $recipient->mobile,
                'recipient_email' => $recipient->email,
                'message' => $rendered,
                'success' => $result['success'],
                'status_message' => $result['message'],
                'provider_response' => $result['raw'],
                'course_id' => $courseId,
                'course_registration_id' => $recipient->id,
                'sent_by' => Auth::guard('admin')->id(),
            ]);

            if ($result['success']) {
                $sentCount++;
            } else {
                $failedCount++;
                $failedNames[] = $recipient->full_name;
            }
        }

        $summary = "تم إرسال {$sentCount} رسالة بنجاح";
        if ($failedCount > 0) {
            $summary .= "، وفشل إرسال {$failedCount}";
        }

        return response()->json([
            'status' => $sentCount > 0 ? 'success' : 'error',
            'message' => $summary,
            'sent' => $sentCount,
            'failed' => $failedCount,
            'failed_names' => $failedNames,
        ]);
    }

}
