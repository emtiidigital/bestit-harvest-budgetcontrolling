<?php

namespace App\Providers;

use App\Console\Commands\CreateRessourcesForecastEvent;
use App\Events\Clients\CreateBudgetControllingFilesEvent;
use App\Events\Generators\File\ExcelFileCreatedEvent;
use App\Listeners\CreateBudgetControllingFileListener;
use App\Listeners\CreateRessourceForecastByEmployeesListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CreateBudgetControllingFilesEvent::class => [
            CreateBudgetControllingFileListener::class
        ],
        ExcelFileCreatedEvent::class => [
            // todo: implement listeners, if needed
        ],
        CreateRessourcesForecastEvent::class => [
            CreateRessourceForecastByEmployeesListener::class
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
