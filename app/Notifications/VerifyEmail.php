<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as UserVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyEmail extends UserVerifyEmail
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }
        $appUrl = config('app.url');
        return (new MailMessage)
            ->subject('Activate Your Account')
            ->markdown('emails.user.verification', [
                'user' => $notifiable,
                'url' => $appUrl,
                'verificationUrl' => $appUrl . $verificationUrl,
            ]);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $token = JWTAuth::fromUser($notifiable);
        $notifiable->forceFill([
            'verification_token' => $token,
        ])->save();
        return "/user/verify?token={$token}";
    }
}
