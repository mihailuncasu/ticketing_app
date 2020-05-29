<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        // Customize the reset password link according the tenant subdomain from where the request was sent;
        $temp_url = url(
                config('app.url').route(
                    'password.reset', [
                        'token' => $this->token,
                        'email' => $notifiable->getEmailForPasswordReset()
                    ], false
                )
            );
        $exploded_url = explode('//', $temp_url, 2);
        $url = $exploded_url[0] . '//' . config('subdomain') . '.' . $exploded_url[1];

        return (new MailMessage)
            ->subject(Lang::getFromJson('Reset Password Notification'))
            ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::getFromJson('Reset Password'), $url)
            ->line(Lang::getFromJson('MIHAIThis password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'));
    }
}
