<?php

namespace App\Providers;

use App\Events\NewOrderRecordEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
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
    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        //SQL调试
        if (app()->isLocal() || app()->environment() == 'dev') {
            $this->listen[ QueryExecuted::class ] = [
                \App\Listeners\QueryListener::class,
            ];
        }
        //新订单录入事件
        $this->listen[ NewOrderRecordEvent::class ] = [
            \App\Listeners\NewOrderRecordListener::class,
        ];
        return $this->listen;
    }
}
