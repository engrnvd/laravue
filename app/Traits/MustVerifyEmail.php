<?php

namespace App\Traits;

use App\Notifications\VerifyEmail;
use Illuminate\Auth\MustVerifyEmail as UserVerifyEmail;

trait MustVerifyEmail
{
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}