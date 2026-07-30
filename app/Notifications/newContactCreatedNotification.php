<?php

namespace App\Notifications;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class newContactCreatedNotification extends Notification
{
    use Queueable;
    protected $obj;
    protected $contact_name;
    protected $created_by;
    protected $notes;
    protected $add_date;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($obj, $contact_name, $created_by, $notes, $add_date)
    {
        //
        $this->obj = $obj;
        $this->contact_name = $contact_name;
        $this->created_by = $created_by;
        $this->notes = $notes;
        $this->add_date = $add_date;

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
            'user'=> $this->created_by,
            'notes'=> $this->notes,
            'date' => $this->add_date,
            'contact_name' => $this->contact_name,
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
