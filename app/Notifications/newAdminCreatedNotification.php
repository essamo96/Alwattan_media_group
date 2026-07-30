<?php

namespace App\Notifications;

use App\Models\Students;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class newAdminCreatedNotification extends Notification
{
    use Queueable;
    protected $obj;
    protected $message;
    protected $title;
    protected $sender_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($obj,$message,$title,$auth)
    {
        //
        $this->obj = $obj;
        $this->message = $message;
        $this->title = $title;
        $this->sender_name = $auth;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        return [
            'contact'=> $this->title,
            'notes'=> $this->message,
            'date' => $this->sender_name,
            // 'sender_name'=> $this->sender_name,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
