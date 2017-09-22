<?php

namespace App\Listeners;

use App\Console\Commands\JiraIssuesRepository;
use App\Generators\Report\RessourceForecastGenerator;

class CreateRessourceForecastByEmployeesListener
{
    const EMPLOYEES = [
        'natascha.nigro@bestit-online.de',
        'marx@bestit-online.de',
        'stephanie.schuster@bestit-online.de',
        'thiesies@bestit-online.de'
    ];

    const PROJECT_NAME = 'Internal - Produktionssteuerung';

    private $jiraIssuesRepository;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->jiraIssuesRepository = new JiraIssuesRepository();
        $this->createRessourceForecastByEmployees(self::EMPLOYEES);
    }

    public function handle()
    {
    }

    /**
     * Main entrypoint for listener to create excel files for every employee
     * to give them an excel overview about their forecast in pt for all
     * services best it provides.
     *
     * @param $employees
     */
    private function createRessourceForecastByEmployees($employees)
    {
        info('Create Ressource Forecast for every given employees.');

        foreach ($employees as $employee) {
            info('Create report for ' . $employee);

            $issues = $this->jiraIssuesRepository->getAllStoriesByProjectNameAndReporterEmail(
                self::PROJECT_NAME,
                $employee
            );

            $report = new RessourceForecastGenerator(
                $issues->getIssues(),
                $employee
            );
            $success = $report->process();

            $success === true ? info('success') : info('failed');
        }
    }
}
