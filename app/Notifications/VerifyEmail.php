<?php

namespace App\Notifications;

use Hyn\Tenancy\Models\Hostname;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail
{

    protected function verificationUrl($notifiable)
    {
        $temporary_url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            ['id' => $notifiable->getKey()]
        );

        $exploded_url = explode('//', $temporary_url, 2);

        return $exploded_url[0] . '//' . config('subdomain') . '.' . $exploded_url[1];
    }
}
