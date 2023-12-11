<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyNotification extends Notification
{
    use Queueable;

    public $url;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $url='')
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $params = [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification())
        ];

        $url =  env('FRONT_APP', $this->url).'/verify-email?';

        foreach($params as $key => $param){
            $url .= "{$key}={$param}&";
        }

        return (new MailMessage)
                    ->subject('Vérification de votre adresse email')
                    ->greeting('Bonjour')
                    ->line('Cliquez sur le bouton ci-dessous pour vérifier votre adresse e-mail et terminer la configuration de votre profil.')
                    ->action('Vérifiez votre adresse email', $url)
                    ->salutation('Cordialement');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
