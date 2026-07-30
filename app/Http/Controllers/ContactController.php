<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Socials;
use App\Models\Settings;
use Mail;

class ContactController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    ///////////////////////////
    public function getIndex() {
        return view('frontend.contact.view', parent::$data);
    }

    //////////////////////////
    public function postContact(Request $request) {
        dd($_POST);
        exit;
        $secet_key = '6LeQyuwZAAAAAIfBwAPZlj0YX6pHA3rlntlZk0Cx';
        $email = $request->get('email');

        if (isset($email) && $email) {
            $email = filter_var($email, FILTER_SANITIZE_STRING);
        } else {
            $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مرة اخري');
            return redirect('contact')->withInput();
        }
        $token = $_POST['token'];
        $action = $_POST['action'];

// call curl to POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $secet_key, 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);

// verify the response
        if ($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
            $data['name'] = $request->get('name');
            $data['email'] = $request->get('email');
            $data['phone'] = $request->get('phone');
            $data['subject'] = $request->get('subject');
            $data['details'] = $request->get('message');

            $validator = Validator::make([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'subject' => $data['subject'],
                        'details' => $data['details']
                            ], [
                        'name' => 'required',
                        'email' => 'required|email',
                        'phone' => 'required',
                        'subject' => 'required',
                        'details' => 'required'
            ]);
            //////////////////////////////////////////////////////////
            if ($validator->fails()) {
                $request->session()->flash('danger', $validator->messages());
                return redirect('contact')->withInput();
            } else {
                $settings = new Settings();
                $info = $settings->getSetting(1);
                if ($info) {
                    if ($info->contact_email) {
                        $sent = Mail::send(['text' => 'emails.welcome'], $data, function ($message) {
                                    $settings = new Settings();
                                    $info = $settings->getSetting(1);
                                    $message->to($info->contact_email, 'العربية للجميع')->subject
                                            ('ايميل تواصل من الموقع');
                                    $message->from('contact@arabicforall.net', 'Site Contact');
                                });
                        if (!$sent) {
                            $request->session()->flash('success', 'تم الارسال بنجاح شكرا لك');
                            return redirect('contact')->withInput();
                        } else {
                            $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مرة اخري');
                            return redirect('contact')->withInput();
                        }
                    } else {
                        $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مرة اخري');
                        return redirect('contact')->withInput();
                    }
                } else {
                    $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مرة اخري');
                    return redirect('contact')->withInput();
                }
            }
        } else {
            $request->session()->flash('danger', 'حدثت مشكلة الرجاء المحاولة مرة اخري');
            return redirect('contact')->withInput();
        }
        ////////////////////////////////////////////
    }

}
