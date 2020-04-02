<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    use Queueable;

    public function toMail($notifiable)
    {
        $url = url(config('app.client_url') . '/password/reset/' . $this->token)
            . '?email=' . urlencode($notifiable->email);
        return (new MailMessage)
            ->line('パスワード変更のリクエストを送信しました')
            ->action('パスワードのリセット', $url)
            ->line('Thank you for using our application!');
    }
}
