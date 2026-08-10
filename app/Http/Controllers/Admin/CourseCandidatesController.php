<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\CourseCandidate;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;

class CourseCandidatesController extends AdminController {

    const ADD_SUCCESS = "نجاح، تمت إضافة المرشحين المحددين";
    const REMOVE_SUCCESS = "نجاح، تم حذف المرشح من الدورة";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'courses';
    }

    //////////////////////////////////////////////
    private function resolveCourse($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return null;
        }
        return (new Course())->getCourse($id);
    }

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
    public function getIndex() {
        parent::$data['active_menu'] = 'course_candidates';
        parent::$data['courses'] = Course::orderBy('name')->get();
        return view('admin.course_candidates.index', parent::$data);
    }

    //////////////////////////////////////////////
    public function getIndexList(Request $request) {
        $courseId = $request->get('course_id');
        $filters = $this->filtersFromRequest($request);
        $hasFilters = count(array_filter($filters, fn($v) => !empty($v))) > 0;

        $matchingRegistrationIds = $hasFilters
            ? (new CourseRegistration())->applyFilters($filters)->pluck('id')
            : null;

        $query = CourseCandidate::query()
            ->with(['course', 'registration'])
            ->whereHas('registration')
            ->when($courseId, function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->when($matchingRegistrationIds !== null, function ($q) use ($matchingRegistrationIds) {
                $q->whereIn('course_registration_id', $matchingRegistrationIds);
            })
            ->orderBy('id', 'desc');

        $datatable = Datatables::of($query);

        $datatable->addColumn('applicant', function ($row) {
            return $row->registration
                ? view('admin.course_registrations.parts.applicant', ['row' => $row->registration])->render()
                : '-';
        });

        $datatable->addColumn('course_name', function ($row) {
            return $row->course->name ?? '-';
        });

        $datatable->addColumn('university', function ($row) {
            return $row->registration->university ?? '-';
        });

        $datatable->addColumn('gpa', function ($row) {
            return $row->registration->gpa ?? '-';
        });

        $datatable->addColumn('actions', function ($row) {
            return '<button type="button" class="btn btn-sm btn-light-danger remove-candidate-btn" data-candidate-id="' . Crypt::encrypt($row->id) . '">'
                . '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> حذف'
                . '</button>';
        });

        $datatable->escapeColumns(['course_name', 'university', 'gpa']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function postRemoveGlobal(Request $request) {
        try {
            $candidateId = Crypt::decrypt($request->get('candidate_id'));
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $candidate = CourseCandidate::find($candidateId);
        if ($candidate) {
            $candidate->delete();
            return response()->json(['status' => 'success', 'message' => self::REMOVE_SUCCESS]);
        }

        return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
    }

    //////////////////////////////////////////////
    public function getManage(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('courses.view'));
        }

        parent::$data['course'] = $course;
        parent::$data['encrypted_id'] = $id;
        // فلاتر جاءت من زر "ترشيح" بشاشة تسجيلات الدورة (تُعبّأ بالفلاتر تلقائياً عند الوصول)
        parent::$data['initial_filters'] = $this->filtersFromRequest($request);
        return view('admin.course_candidates.manage', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            return response()->json(['error' => self::NOT_FOUND], 404);
        }

        $filters = $this->filtersFromRequest($request);
        $candidateRegistrationIds = $course->candidates()->pluck('course_registration_id');

        $registration = new CourseRegistration();
        // المرشحون فعلياً لهذه الدورة يُستبعدون من نتائج البحث بالكامل (مو بس checkbox معطّل)
        // حتى ما يتكرر ظهور نفس الاسم بالفلترة اللاحقة.
        $query = $registration->applyFilters($filters)
            ->when($candidateRegistrationIds->isNotEmpty(), function ($q) use ($candidateRegistrationIds) {
                $q->whereNotIn('id', $candidateRegistrationIds);
            })
            ->orderBy('id', 'desc');
        $datatable = Datatables::of($query);

        $datatable->addColumn('applicant', function ($row) {
            return view('admin.course_registrations.parts.applicant', ['row' => $row])->render();
        });

        $datatable->editColumn('marital_status', function ($row) {
            return $row->maritalStatusLabel();
        });

        $datatable->addColumn('select', function ($row) {
            return '<div class="form-check form-check-custom form-check-solid">'
                . '<input class="form-check-input candidate-checkbox" type="checkbox" value="' . $row->id . '">'
                . '</div>';
        });

        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getCurrent(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            return response()->json(['error' => self::NOT_FOUND], 404);
        }

        $candidates = $course->candidates()
            ->with('registration')
            ->get()
            ->filter(fn($candidate) => $candidate->registration !== null)
            ->map(function ($candidate) {
                $r = $candidate->registration;
                return [
                    'candidate_id' => Crypt::encrypt($candidate->id),
                    'full_name' => $r->full_name,
                    'age' => $r->age,
                    'gender' => $r->genderLabel(),
                    'gpa' => $r->gpa,
                    'university' => $r->university,
                ];
            })
            ->values();

        return response()->json(['data' => $candidates]);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        $ids = (array) $request->get('ids', []);
        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
        }

        $validRegistrationIds = CourseRegistration::whereIn('id', $ids)->pluck('id');
        foreach ($validRegistrationIds as $registrationId) {
            CourseCandidate::addCandidate($course->id, $registrationId);
        }

        return response()->json(['status' => 'success', 'message' => self::ADD_SUCCESS]);
    }

    //////////////////////////////////////////////
    // يضيف كل المتقدمين المطابقين للفلاتر الحالية دفعة واحدة (بدون التقيد بصفحة الجدول الحالية).
    public function postAddAll(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        $filters = $this->filtersFromRequest($request);
        $registration = new CourseRegistration();
        $matchingIds = $registration->applyFilters($filters)->pluck('id');

        if ($matchingIds->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
        }

        $newCount = 0;
        foreach ($matchingIds as $registrationId) {
            if (CourseCandidate::addCandidate($course->id, $registrationId)) {
                $newCount++;
            }
        }

        $message = $newCount > 0
            ? 'نجاح، تمت إضافة ' . $newCount . ' مرشح جديد (من أصل ' . $matchingIds->count() . ' مطابق للفلاتر)'
            : 'كل النتائج المطابقة (' . $matchingIds->count() . ') مرشحون لهذه الدورة أصلاً';
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    //////////////////////////////////////////////
    public function postRemove(Request $request, $id) {
        $course = $this->resolveCourse($id);
        if (!$course) {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }

        try {
            $candidateId = Crypt::decrypt($request->get('candidate_id'));
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $candidate = CourseCandidate::where('course_id', $course->id)->find($candidateId);
        if ($candidate) {
            $candidate->delete();
            return response()->json(['status' => 'success', 'message' => self::REMOVE_SUCCESS]);
        }

        return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
    }

}
