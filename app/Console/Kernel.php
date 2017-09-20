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
        // create budget controlling excel files one time a day
        $schedule->command('bestit:create:budget_controlling')
            ->dailyAt('01:00');

        // send reports to key account management, every day
        // @todo: implement

        // send reports to customers weekly, every monday afternoon
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
