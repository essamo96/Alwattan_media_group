<?php

namespace App\Http\Controllers;

use App\Events\NewCourseRegistration;
use App\Models\CourseRegistration;
use App\Models\Partners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseRegistrationController extends Controller {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم إرسال طلبك بنجاح وسيتم التواصل معك عند الحاجة.";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية، الرجاء المحاولة مرة اخرى.";

    ///////////////////////////
    public function getIndex() {
        $partners = new Partners();
        parent::$data['partners'] = $partners->getAllPartners();
        return view('frontend.course_registration.view', parent::$data);
    }

    //////////////////////////
    public function postRegister(Request $request) {
        $data = $request->only([
            'full_name', 'national_id', 'gender', 'birth_date', 'general_specialization',
            'specific_specialization', 'graduation_year', 'university', 'gpa', 'nationality',
            'current_address', 'birth_place', 'employer', 'marital_status', 'mobile', 'email',
            'disability_description', 'citizen_type', 'unrwa_card_number',
        ]);
        // القيمة الخام من checkbox النموذج ("1" اذا مفعّل) تُستخدم بالتحقق كما هي،
        // وتتحول لـ boolean حقيقي بعد نجاح التحقق فقط (أسفل الدالة).
        $data['has_disability'] = $request->input('has_disability', '0');

        $currentYear = (int) date('Y');

        $validator = Validator::make($data, [
                    'full_name' => 'required|string|min:6|max:150',
                    // unique:...,NULL,id,deleted_at,NULL يتجاهل التسجيلات المحذوفة (soft delete) عند فحص التكرار،
                    // بنفس الأسلوب المتّبع بباقي الكونترولرز بهذا المشروع (مثال: AdvertisementsController).
                    'national_id' => 'required|digits_between:9,10|unique:course_registrations,national_id,NULL,id,deleted_at,NULL',
                    'gender' => 'required|in:male,female',
                    'birth_date' => 'required|date|before:' . date('Y-m-d', strtotime('-16 years')),
                    'general_specialization' => 'required|string|max:150',
                    'specific_specialization' => 'required|string|max:150',
                    'graduation_year' => 'required|integer|min:1970|max:' . $currentYear,
                    'university' => 'required|string|max:150',
                    'gpa' => 'required|numeric|min:0|max:100',
                    'nationality' => 'required|string|max:100',
                    'current_address' => 'required|string|max:255',
                    'birth_place' => 'required|string|max:150',
                    'employer' => 'required|string|max:150',
                    'marital_status' => 'required|in:single,married,divorced,widowed',
                    'mobile' => ['required', 'regex:/^05[0-9]{8}$/', 'unique:course_registrations,mobile,NULL,id,deleted_at,NULL'],
                    'email' => 'required|email:filter|max:150|unique:course_registrations,email,NULL,id,deleted_at,NULL',
                    'has_disability' => 'nullable|in:0,1',
                    'disability_description' => 'required_if:has_disability,1|nullable|string|max:500',
                    'citizen_type' => 'required|in:citizen,refugee',
                    'unrwa_card_number' => 'required_if:citizen_type,refugee|nullable|string|max:50',
                        ], [
                    'required' => 'هذا الحقل مطلوب',
                    'national_id.digits_between' => 'رقم الهوية يجب ان يتكون من 9 الى 10 ارقام',
                    'national_id.unique' => 'رقم الهوية هذا مسجّل مسبقاً',
                    'birth_date.before' => 'تاريخ الميلاد غير منطقي',
                    'gpa.max' => 'قيمة المعدل غير صحيحة',
                    'mobile.regex' => 'رقم الجوال يجب ان يكون بصيغة فلسطينية صحيحة (05xxxxxxxx)',
                    'mobile.unique' => 'رقم الجوال هذا مسجّل مسبقاً',
                    'email.unique' => 'البريد الإلكتروني هذا مسجّل مسبقاً',
                    'disability_description.required_if' => 'يرجى وصف الإعاقة الصحية',
                    'citizen_type.required' => 'يرجى تحديد هل المسجل مواطن أم لاجئ',
                    'unrwa_card_number.required_if' => 'يرجى إدخال رقم تسجيل بطاقة الوكالة',
        ]);

        if ($validator->fails()) {
            return redirect(route('course_registration.view'))
                            ->withErrors($validator)
                            ->withInput();
        }

        // تطبيع قيمة الـ checkbox الخام ("1"/"0") الى boolean حقيقي قبل الحفظ.
        $data['has_disability'] = $data['has_disability'] === '1';
        if (!$data['has_disability']) {
            $data['disability_description'] = null;
        }
        if ($data['citizen_type'] !== 'refugee') {
            $data['unrwa_card_number'] = null;
        }

        $registration = new CourseRegistration();
        $saved = $registration->addRegistration($data);

        if ($saved) {
            try {
                event(new NewCourseRegistration($saved));
            } catch (\Throwable $e) {
                // Broadcasting must never block a successful registration.
            }

            $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
            return redirect(route('course_registration.view'))->with('registered', true);
        }

        $request->session()->flash('danger', self::EXECUTION_ERROR);
        return redirect(route('course_registration.view'))->withInput();
    }

}
