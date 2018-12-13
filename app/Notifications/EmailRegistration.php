<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class EmailRegistration extends Notification
{
    use Queueable;

    public $user;
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) 
    {
        return (new MailMessage)
            ->from('uloszone@gmail.com', 'Admin')
            ->subject('Welcome to UlosZone')
            ->greeting('Horas!')
            ->line('Klik tombol dibawah untuk memverifikasi akun Anda')
            ->action(
                'Verifikasi Email',
                $this->verificationUrl()
            )
            ->line('Jika Anda tidak merasa mendaftar di UlosZone.com, Anda tidak perlu memverifikasi email.');
    }

    protected function verificationUrl()
    {
        return URL::temporarySignedRoute(
            'email.verify',
            Carbon::now()->addMinutes(30),
            ['id' => $this->user->id, 'token' => $this->user->emailVerification->token]
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
        return [
            //
        ];
    }
}
