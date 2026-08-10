<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model {

    protected $table = 'sms_logs';
    protected $fillable = [
        'recipient_name', 'recipient_mobile', 'recipient_email', 'message',
        'success', 'status_message', 'provider_response',
        'course_id', 'course_registration_id', 'sent_by',
    ];
    protected $casts = [
        'success' => 'boolean',
    ];

}
