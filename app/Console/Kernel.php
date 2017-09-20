<?php

namespace App\Console;

use App\Console\Commands\CreateBudgetControllingFilesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreateBudgetControllingFilesCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // create budget controlling excel files one time a day, weekdays
        $schedule->command('bestit:create:budget_controlling')
            ->weekdays()
            ->dailyAt('01:00');

        // send reports to key account management, every day
        // report should contain:
        //      - high adjustments in short time (10% of overall budget used in one day)
        //      - projects below
        //          - 25%
        //          - 50%
        //          - 75%
        //          - 100%
        //      - projects above 100%
        // @todo: implement

        // generate additional report including internal costs
        // @todo: implement
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
