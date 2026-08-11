<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
////////////////////////////////////
use App\Exports\CourseCandidatesExport;
use App\Models\Course;

class CoursesController extends AdminController {

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";

    //////////////////////////////////////////////
    public function __construct() {
        parent::__construct();
        parent::$data['active_menu'] = 'courses';
    }

    //////////////////////////////////////////////
    public function getIndex() {
        parent::$data['export_columns'] = CourseCandidatesExport::availableColumns();
        return view('admin.courses.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request) {
        $name = $request->get('name', NULL);
        $courses = new Course();
        $info = $courses->getSearchCourses($name);
        $datatable = Datatables::of($info);

        $datatable->editColumn('days_of_week', function ($row) {
            return $row->daysOfWeekLabel();
        });

        $datatable->editColumn('start_date', function ($row) {
            return optional($row->start_date)->format('Y-m-d');
        });

        $datatable->editColumn('end_date', function ($row) {
            return optional($row->end_date)->format('Y-m-d');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.courses.parts.status', $data)->render();
        });

        $datatable->addColumn('candidates_count', function ($row) {
            $data['id'] = $row->id;
            $data['count'] = $row->candidates()->count();

            return view('admin.courses.parts.candidates_count', $data)->render();
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['name'] = $row->name;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.courses.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd() {
        parent::$data['days_of_week_labels'] = Course::daysOfWeekLabels();
        return view('admin.courses.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request) {
        $save_data = $request->except('_token');
        $save_data['status'] = $request->get('status', 0) ? 1 : 0;
        $save_data['days_of_week'] = $request->get('days_of_week', []);

        $validator = Validator::make($save_data, [
                    'name' => 'required',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'days_of_week' => 'nullable|array',
        ]);
        //////////////////////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('courses.add'))->withInput();
        } else {
            $add = Course::create($save_data);
            if ($add) {
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('courses.view'));
            } else {
                $request->session()->flash('danger', self::EXECUTION_ERROR);
                return redirect(route('courses.add'))->withInput();
            }
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('courses.view'));
        }
        //////////////////////////////////////////////
        $courses = new Course();
        $info = $courses->getCourse($id);
        if ($info) {
            parent::$data['info'] = $info;
            parent::$data['days_of_week_labels'] = Course::daysOfWeekLabels();
            return view('admin.courses.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('courses.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postEdit(Request $request, $id) {
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('courses.view'));
        }
        /////////////////////////////
        $info = Course::findOrFail($id);

        if ($info) {
            $save_data = $request->except('_token');
            $save_data['status'] = $request->get('status', 0) ? 1 : 0;
            $save_data['days_of_week'] = $request->get('days_of_week', []);

            $validator = Validator::make($save_data, [
                        'name' => 'required',
                        'start_date' => 'required|date',
                        'end_date' => 'required|date|after_or_equal:start_date',
                        'days_of_week' => 'nullable|array',
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('courses.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $update = $info->update($save_data);
                if ($update) {
                    $request->session()->flash('success', self::UPDATE_SUCCESS);
                    return redirect(route('courses.view'));
                } else {
                    $request->session()->flash('danger', self::EXECUTION_ERROR);
                    return redirect(route('courses.edit', ['id' => $encrypted_id]))->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('courses.view'));
        }
    }

    ////////////////////////////////////////////////
    public function postDelete(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////////////
        $courses = new Course();
        $info = $courses->getCourse($id);
        if ($info) {
            $delete = $courses->deleteCourse($info);
            if ($delete) {
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////////
    public function postStatus(Request $request) {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////////////
        $courses = new Course();
        $info = $courses->getCourse($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $courses->updateStatus($id, 1);
                if ($update) {
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $courses->updateStatus($id, 0);
                if ($update) {
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

}
