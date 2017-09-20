<?php

namespace App\Console\Commands;

use App\Events\Clients\CreateBudgetControllingFilesEvent;
use App\Repository\Clients\ClientsDataRepository;
use Illuminate\Console\Command;

/**
 * Class CreateBudgetControllingFilesCommand
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Console\Commands
 */
class CreateBudgetControllingFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bestit:create:budget_controlling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch needed data by clients and create files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('Creating Budget Controlling files for clients.');

        $clients = new ClientsDataRepository();

        event(new CreateBudgetControllingFilesEvent(
            $clients->getAllClients()
        ));
    }
}
