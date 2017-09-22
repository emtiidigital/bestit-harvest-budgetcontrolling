<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRessourcesForecastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bestit:create:ressources_forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ressources forecast as excel report.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Start creating resources forecast.");

        event(new CreateRessourcesForecastEvent());
    }
}
