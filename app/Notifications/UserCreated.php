<?php

namespace App\Notifications;

use Session;
use Auth;
use View;
use Config;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

use Request as _Request;

class UserCreated extends Notification
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database'];

       $person = $notifiable->person;

       if ($person && $person->email && $person->email != '' && Config::get('constants.EMAIL_NOTIF') == 1)
            $via[] = 'mail';

       return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = 'Welcome To Maze!';

        return (new MailMessage)->subject($subject)->view(
          'emails.user-created', ['base_url' => _Request::root(), 'user' => $this->user, 'subject' => $subject]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->user->toArray();
    }
}
