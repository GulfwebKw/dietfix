<?php

namespace App\Providers;

use App\Events\RegisterUser;
use App\Events\Sms;
use App\Listeners\IncrementCreditReferralUser;
use App\Listeners\SendSms;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RegisterUser::class=>[
            IncrementCreditReferralUser::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Sms::class=>[
            SendSms::class
        ]

    ];
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
