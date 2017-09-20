<?php

namespace App\Listeners;

use App\Generators\Report\ClientBudgetReportGenerator;
use App\Events\Clients\CreateBudgetControllingFilesEvent;
use BestIt\Harvest\Models\Clients\Client;
use BestIt\Harvest\Models\Clients\Clients;

/**
 * Class CreateBudgetControllingFileListener
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Listeners
 */
class CreateBudgetControllingFileListener
{
    /**
     * Handle the event. Main entry point for this listener.
     *
     * @param CreateBudgetControllingFilesEvent $event
     * @return void
     */
    public function handle(CreateBudgetControllingFilesEvent $event)
    {
        // get harvest clients from event
        $clients = $event->getClients();

        // purpose of listener
        $this->createClientBudgetReports($clients);
    }

    /**
     * Main logical entry point for creation of files. Clients where given by event,
     * so we iterate every given client and their assigned projects. For every project
     * we fetch added time entries with their given tasks. We multiply added time entries
     * with the task rate of assigned project to calculate real used budget.
     *
     * @param $clients
     */
    private function createClientBudgetReports(Clients $clients)
    {
        /** @var Client $client */
        foreach ($clients as $client) {
            $report = new ClientBudgetReportGenerator($client);
            $success = $report->process();

            $success === true ? info('success') : info('failed');
        }
    }
}
