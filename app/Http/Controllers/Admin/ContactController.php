<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Contact;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Notifications\newContactCreatedNotification;
use Illuminate\Contracts\Encryption\DecryptException;

class ContactController extends AdminController
{

    const INSERT_SUCCESS_MESSAGE = "نجاح، تم الإضافة بتجاح";
    const UPDATE_SUCCESS = "نجاح، تم التعديل بنجاح";
    const DELETE_SUCCESS = "نجاح، تم الحذف بنجاح";
    const PASSWORD_SUCCESS = "نجاح، تم تغيير كلمة المرور بنجاح";
    const EXECUTION_ERROR = "عذراً، حدث خطأ أثناء تنفيذ العملية";
    const NOT_FOUND = "عذراً،لا يمكن العثور على البيانات";
    const ACTIVATION_SUCCESS = "نجاح، تم التفعيل بنجاح";
    const DISABLE_SUCCESS = "نجاح، تم التعطيل بنجاح";

    //////////////////////////////////////////////
    public function __construct()
    {
        parent::__construct();
        parent::$data['active_menu'] = 'contact';
    }

    //////////////////////////////////////////////
    public function getIndex()
    {
        $user = new User();
        $info = $user->getUsersActive();
        parent::$data['users'] = $info;

        return view('admin.contact.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getYourContact()
    {
        $user = new User();
        $info = $user->getUsersActive();
        parent::$data['users'] = $info;

        return view('admin.contact.your_contact', parent::$data);
    }

    //////////////////////////////////////////////
    public function getRememberContact()
    {
        $user = new User();
        $info = $user->getUsersActive();
        parent::$data['users'] = $info;
        // dd(Carbon::now());
        return view('admin.contact.remember_contact', parent::$data);
    }

    //////////////////////////////////////////////
    //////////////////////////////////////////////
    public function getBooks()
    {
        return view('frontend.contact.view', parent::$data);
    }

    //////////////////////////////////////////////
    public function getList(Request $request)
    {
        $user = new Contact();

        $name = $request->get('name');
        $typesC = $request->get('typesC');
        $byUser = $request->get('byUser');
        if (auth()->user()->can('admin.contact.viewAll')) {
            $info = $user->getSearchContact($name, $typesC, $byUser);
        } else {
            $info = $user->getSearchContactByAuthName($name, $typesC, $byUser, Auth::guard('admin')->user()->name);
        }

        $datatable = Datatables::of($info);

        $datatable->editColumn('name', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });
        $datatable->editColumn('created_by', function ($row) {
            if (!is_null($row->user)) {
                return $row->user->username;
            }
        });
        $datatable->editColumn('contact_type', function ($row) {
            return (!empty($row->contact_type) ? $row->contact_type : 'N/A');
        });
        $datatable->editColumn('email', function ($row) {
            return (!empty($row->email) ? $row->email : '<strong><i class="bi bi-exclamation-circle empty"></i></strong><span> لا يوجد </span>');
        });
        $datatable->editColumn('master', function ($row) {
            return (!empty($row->master) ? $row->master : '<strong><i class="bi bi-exclamation-circle empty"></i></strong><span> لا يوجد </span>');
        });
        $datatable->editColumn('mobile', function ($row) {
            return (!empty($row->mobile) ? $row->mobile : '<strong><i class="bi bi-exclamation-circle empty"></i></strong><span> لا يوجد </span>');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.contact.parts.status', $data)->render();
        });
        $datatable->editColumn('created_by', function ($row) {
            return '
            <a id="contactInfo"  data-name="' . $row->created_by . '" data-date="' . $row->add_date . '"  class="btn btn-info btn-sm" style=" color: #ffff;background-color: #0b41d8;" >
            <i class="bi bi-caret-left-fill"></i> ' . $row->created_by . '</a>';
            // if (!is_null($row->created_by)) {
            // return '<a class="btn dark  btn-sm" style="color:#fbff04; font-size: 13px;"> ' . $row->created_by . ' | ' . '<span style="color:aqua;">' . $row->add_date . '<span>' . '  </a> ';
            // }
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['data'] = $row;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.contact.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    //    public function getAuthAddedList(Request $request) {
    //        $user = new Contact();
    //        $auth_name = Auth::guard('admin')->user()->name;
    //        $name = $request->get('name');
    //        $typesC = $request->get('typesC');
    //        $byUser = $request->get('byUser');
    //
    //        $info = $user->getSearchContactByAuthName($name, $typesC, $byUser, $auth_name);
    //
    //        $datatable = Datatables::of($info);
    //
    //        $datatable->editColumn('name', function ($row) {
    //            return (!empty($row->name) ? $row->name : 'N/A');
    //        });
    //        $datatable->editColumn('created_by', function ($row) {
    //            if (!is_null($row->user)) {
    //                return $row->user->username;
    //            }
    //        });
    //        $datatable->editColumn('contact_type', function ($row) {
    //            return (!empty($row->contact_type) ? $row->contact_type : 'N/A');
    //        });
    //        $datatable->editColumn('master', function ($row) {
    //            return (!empty($row->master) ? $row->master : 'N/A');
    //        });
    //        $datatable->editColumn('mobile', function ($row) {
    //            return (!empty($row->mobile) ? $row->mobile : 'N/A');
    //        });
    //
    //        $datatable->editColumn('status', function ($row) {
    //            $data['id'] = $row->id;
    //            $data['status'] = $row->status;
    //
    //            return view('admin.contact.parts.status', $data)->render();
    //        });
    //        $datatable->editColumn('created_by', function ($row) {
    //            // if (!is_null($row->created_by)) {
    //            return '<a class="btn dark  btn-sm" style="color:#fbff04; font-size: 13px;"> ' . $row->created_by . ' | ' . '<span style="color:aqua;">' . $row->add_date . '<span>' . '  </a> ';
    //            // }
    //        });
    //
    //        $datatable->addColumn('actions', function ($row) {
    //            $data['id'] = $row->id;
    //            $data['btn_class'] = parent::$data['btn_class'];
    //
    //            return view('admin.contact.parts.actions', $data)->render();
    //        });
    //        $datatable->escapeColumns(['*']);
    //        return $datatable->make(true);
    //    }
    //////////////////////////////////////////////
    public function getRememberContactList(Request $request)
    {
        $user = new Contact();
        $auth_name = Auth::guard('admin')->user()->name;
        $name = $request->get('name');
        $typesC = $request->get('typesC');
        $byUser = $request->get('byUser');

        $info = $user->getSearchContactByAuthNameRemember($name, $typesC, $byUser, $auth_name);

        $datatable = Datatables::of($info);

        $datatable->editColumn('name', function ($row) {
            return (!empty($row->name) ? $row->name : 'N/A');
        });
        $datatable->editColumn('created_by', function ($row) {
            if (!is_null($row->user)) {
                return $row->user->username;
            }
        });
        $datatable->editColumn('contact_type', function ($row) {
            return (!empty($row->contact_type) ? $row->contact_type : 'N/A');
        });
        $datatable->editColumn('master', function ($row) {
            return (!empty($row->master) ? $row->master : 'N/A');
        });
        $datatable->editColumn('mobile', function ($row) {
            return (!empty($row->mobile) ? $row->mobile : 'N/A');
        });

        $datatable->editColumn('status', function ($row) {
            $data['id'] = $row->id;
            $data['status'] = $row->status;

            return view('admin.contact.parts.status', $data)->render();
        });
        $datatable->editColumn('created_by', function ($row) {
            // if (!is_null($row->created_by)) {

            return '<a class="btn dark  btn-sm" style="color:#fbff04; font-size: 13px;"> ' . $row->created_by . ' | ' . '<span style="color:aqua;">' . $row->add_date . '<span>' . '  </a> ';
            // }
        });

        $datatable->addColumn('actions', function ($row) {
            $data['id'] = $row->id;
            $data['btn_class'] = parent::$data['btn_class'];

            return view('admin.contact.parts.actions', $data)->render();
        });
        $datatable->escapeColumns(['*']);
        return $datatable->make(true);
    }

    //////////////////////////////////////////////
    public function getAdd()
    {
        $contact = new Contact();
        parent::$data['contact'] = $contact->getAllActiveContact();
        $language = new Language();
        parent::$data['languages'] = $language->getAllLanguages();
        return view('admin.contact.add', parent::$data);
    }

    //////////////////////////////////////////////
    public function postAdd(Request $request)
    {
        // dd($_POST);
        $save_data = $request->all();
        $save_data['created_by'] = Auth::guard('admin')->user()->name;
        $save_data['add_date'] = Carbon::now()->format('Y-m-d H:i:s');
        $validator = Validator::make([
            'name' => $save_data['name'],
            'contact_type' => $save_data['contact_type'],
            'mobile' => $save_data['mobile'],
            'master' => $save_data['master'],
        ], [
            'name' => 'required',
            'contact_type' => 'required',
            'mobile' => ['regex:/^\d{10,}$/','min:10' , 'numeric','nullable'],
            'master' => 'required',
        ]);
        ////////////////////////////////////////
        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->messages());
            return redirect(route('contact.add'))->withInput();
        } else {
            $opj = new Contact;
            $add = $opj::create($save_data);
            if ($add) {
                $contact_name = $add->name;
                $created_by = $add->created_by;
                $notes = $add->notes;
                $add_date = $add->add_date;
                $add->notify(new newContactCreatedNotification($add, $contact_name, $created_by, $notes, $add_date));
                $this->clearCache();
                $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                return redirect(route('contact.view'));
            }
        }
    }

    //////////////////////////////////////////////
    public function getContactInfo(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('your.contact.view'));
        }
        $contact = new Contact();
        $info = $contact->getContact($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.contact.Info', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('your.contact.view'));
        }
    }

    //////////////////////////////////////////////
    public function getEdit(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('contact.view'));
        }
        $contact = new Contact();
        $info = $contact->getContact($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.contact.edit', parent::$data);
        } else {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('contact.view'));
        }
    }

    //////////////////////////////////////////////
    public function postEdit(Request $request, $id)
    {
        // dd($_POST);
        try {
            $encrypted_id = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            $request->session()->flash('danger', self::NOT_FOUND);
            return redirect(route('your.contact.view'));
        }

        $contact = new Contact();
        $info = $contact->getContact($id);

        if ($info) {
            $save_data = $request->all();
            $save_data['created_by'] = Auth::guard('admin')->user()->name;
            $save_data['add_date'] = Carbon::now()->format('Y-m-d H:i:s');
            $validator = Validator::make([
                'name' => $save_data['name'],
                'contact_type' => $save_data['contact_type'],
                'mobile' => $save_data['mobile'],
                'master' => $save_data['master'],
            ], [
                'name' => 'required',
                'contact_type' => 'required',
                // 'mobile' => 'required|numeric',
                'mobile' => ['nullable', 'regex:/^\d{10,}$/', 'min:10', 'numeric'],
                'master' => 'required',
            ]);
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect(route('contact.edit', ['id' => $encrypted_id]))->withInput();
            } else {
                $contact = Contact::findOrFail($id);
                $update = $contact->update($save_data);
                if ($update) {
                    $this->clearCache();
                    $request->session()->flash('success', self::INSERT_SUCCESS_MESSAGE);
                    return redirect(route('contact.view'));
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        }
    }

    //////////////////////////////////////////////
    public function postStatus(Request $request)
    {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $contact = new Contact();
        $info = $contact->getContact($id);
        if ($info) {
            $status = $info->status;
            if ($status == 0) {
                $update = $contact->updateStatus($id, 1);
                if ($update) {
                    // $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::ACTIVATION_SUCCESS, 'type' => 'yes']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            } else {
                $update = $contact->updateStatus($id, 0);
                if ($update) {
                    // $this->clearCache();
                    ///////////////////////////////////////////////////////////
                    return response()->json(['status' => 'success', 'message' => self::DISABLE_SUCCESS, 'type' => 'no']);
                } else {
                    return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
                }
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    //////////////////////////////////////////////
    public function postDelete(Request $request)
    {
        $id = $request->get('id');
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        /////////////////////////////
        $contact = new Contact();
        $info = $contact->getContact($id);
        if ($info) {
            $delete = $contact->deleteContact($info);
            if ($delete) {
                $this->clearCache();
                ///////////////////////////////////////////////////////////
                return response()->json(['status' => 'success', 'message' => self::DELETE_SUCCESS]);
            } else {
                return response()->json(['status' => 'error', 'message' => self::EXECUTION_ERROR]);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => self::NOT_FOUND]);
        }
    }

    /////////////////////////////////////////
    public function clearCache()
    {
        // Cache::forget('contact');
        // $contact = new Contact();
        // $info = $contact->getAllActiveContact();
        // Cache::forever('contact', $info);
        // foreach ($info as $row) {
        //     Cache::forget('category_info_' . $row->id);
        //     ////////////////////////////////////////
        //     $category_info = $contact->getActiveContact($row->id);
        //     Cache::forever('category_info_' . $row->id, $category_info);
        // }
    }

    public function printContact($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response()->json(['status' => 'error', 'message' => 'Error Decode']);
        }
        $obj = new Contact();
        $info = $obj->getContact($id);
        if ($info) {
            parent::$data['info'] = $info;
            return view('admin.contact.print', parent::$data);
        }
    }

    public function printAllContact()
    {
        $contact_obj = new Contact();
        if (auth()->user()->can('admin.contact.viewAll')) {
            $info = $contact_obj->getSearchContact(NULL, NULL, NULL);
        } else {
            $info = $contact_obj->getSearchContactByAuthName(NULL, NULL, NULL, Auth::guard('admin')->user()->name);
        }

        parent::$data['info'] = $info;
        return view('admin.contact.printall', parent::$data);
    }
    ////////////////////////////
    public function getTodayNotifications()
    {
        parent::$data['active_menu'] = 'notification';
        $today = now()->format('Y-m-d');
        $notifications = Contact::whereDate('created_at', $today)
        ->whereNull('deleted_at')
        ->get();
        // dd($notifications);

        parent::$data['notifications'] = $notifications;
        return view('admin.notify.view', parent::$data);
    }
}
