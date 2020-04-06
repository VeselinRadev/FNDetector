<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
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
        'App\Events\ArticleSearched' => [
            'App\Listeners\ExtractText',
        ],
        'App\Events\DataCollected' => [
            'App\Listeners\GetDateListener',
            'App\Listeners\DetectKeywords',
            'App\Listeners\SpellChecking',
            'App\Listeners\CheckTextQuality',
            'App\Listeners\CheckLink',
            'App\Listeners\GetGoogleSearchResults',
            'App\Listeners\CheckForConjunction',
            'App\Listeners\ProcessResults',
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
}
