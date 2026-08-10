<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CourseRegistrationsExport;
use App\Models\Course;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class CourseRegistrationsController extends AdminController {

    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'course_registrations';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        parent::$data['courses'] = Course::where('status', 1)->orderBy('name')->get();
        parent::$data['export_columns'] = CourseRegistrationsExport::availableColumns();
        return view('admin.course_registrations.view', parent::$data);
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
    public function getList(Request $request) {
        $filters = $this->filtersFromRequest($request);

        $registration = new CourseRegistration();
        $query = $registration->applyFilters($filters)->orderBy('id', 'desc');
        $datatable = Datatables::of($query);

        $datatable->addColumn('applicant', function ($row) {
            return view('admin.course_registrations.parts.applicant', ['row' => $row])->render();
        });

        $datatable->editColumn('marital_status', function ($row) {
            return $row->maritalStatusLabel();
        });

        $datatable->editColumn('created_at', function ($row) {
            return optional($row->created_at)->format('Y-m-d H:i');
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.course_registrations.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getShow(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('course_registrations.view'));
        }

        $registration = new CourseRegistration();
        $info = $registration->getRegistration($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.course_registrations.show', parent::$data);
        }

        $request->session()->flash('danger', self::NOT_FOUND);
        return redirect(route('course_registrations.view'));
    }

    ////////////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }

        $registration = new CourseRegistration();
        $info = $registration->getRegistration($id);
        if ($info) {
            $delete = $registration->deleteRegistration($info);
            if ($delete) {
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            }
            return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
        }

        return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
    }

    //////////////////////////////////////////////
    public function getExport(Request $request) {
        $filters = $this->filtersFromRequest($request);
        $columns = $request->get('columns'); // مصفوفة مفاتيح الأعمدة بالترتيب المختار من المستخدم، أو null = كل الأعمدة
        $fileName = 'تسجيلات_الدورة_' . date('Y-m-d_H-i') . '.xlsx';

        return Excel::download(new CourseRegistrationsExport($filters, $columns), $fileName);
    }

}
