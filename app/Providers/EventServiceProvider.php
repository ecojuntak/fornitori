<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\UserRegisteredEvent::class => [\App\Listeners\SendVerificationEmail::class],
        \App\Events\OrderCreatedEvent::class => [
            \App\Listeners\SendOrderCreatedToCustomer::class,
            \App\Listeners\SendOrderCreatedToMerchant::class
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
