<?php

namespace App\Generators\Report;

use App\Generators\File\ExcelFileGenerator;
use App\Repository\Budget\BudgetDataRepository;
use App\Repository\Projects\ProjectsDataRepository;
use BestIt\Harvest\Models\Clients\Client;
use BestIt\Harvest\Models\Projects\Project;
use BestIt\Harvest\Models\Projects\Projects;
use Carbon\Carbon;

/**
 * Class ClientBudgetReportGenerator
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Generators\Report
 */
class ClientBudgetReportGenerator implements ReportGeneratorInterface
{
    /** @var Client $client */
    private $client;
    private $projectsRepository;
    private $budgetRepository;

    /**
     * ClientBudgetReportGenerator constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
        $this->projectsRepository = new ProjectsDataRepository();
        $this->budgetRepository = new BudgetDataRepository();
    }

    /**
     * We process only one client at once. First we fetch the client data,
     * then we get all assigned projects of this client.
     * Every project has its own set of budget data
     * so we need to fetch, calculate and build the report data.
     * At least, we create an excel file for this client, where every
     * row is a project with all calculated data we generated.
     *
     * @return bool
     */
    public function process(): bool
    {
        // get client we need to fetch data for
        $client = $this->client;

        // get projects assigned to client id from internal generator
        $projects = $this->projectsRepository->getProjectsByClientId($client->id);

        // get all projects with given budget data per project,
        // ready for file generation.
        $data = $this->getBaseAndBudgetDataOfGivenProjects($projects);

        // create excel file
        if ($client && $data) {
            $generator = new ExcelFileGenerator();
            $generator->setFileNamePrefix($client->id);
            $generator->setFileName($client->name);
            $generator->setFileData($data);
            $generator->generate();

            return true;
        }

        return false;
    }

    /**
     * Iterate all given projects and get all main project & calculated budget data.
     * Summarize all projects and return.
     *
     * @param Projects $projects
     * @return array
     */
    private function getBaseAndBudgetDataOfGivenProjects(Projects $projects): array
    {
        $projectData = [];

        /** @var Project $project */
        foreach ($projects as $project) {
            $mainData = $this->getMainProjectData($project);
            $budgetData = $this->getCalculatedProjectBudgetData($project);

            $projectData[] = array_merge($mainData, $budgetData);
        }

        return $projectData;
    }

    /**
     * Accepts project data
     * @param Project $project
     * @return array
     */
    private function getMainProjectData(Project $project): array
    {
        setlocale(LC_MONETARY, 'de_DE');

        $projectBudget = $project->budget;

        // if project->budget is less than 1000, we assume budget is in hours,
        // so need to multiply by hourly rate for overall budget in euros
        if ($projectBudget < 1000) {
            $projectBudget *= $project->hourlyRate;
        }

        return [
            'Leistungsschein Nummer' => $project->code,
            'Leistungsschein Bezeichnung' => $project->name,
            'LS beauftragt' => Carbon::parse($project->createdAt)->format('d/m/Y'),
            'Kostenindikation' => money_format('%!n €', $projectBudget),
            'Status' => $project->notes .'%',
        ];
    }

    /**
     * Accepts project data and calculates different data. Basically it calculates:
     * - used budget
     * - calculated needed budget (forecast)
     * - budget difference between calculated and used budget
     *
     * @param Project $project
     *
     * @return array
     */
    private function getCalculatedProjectBudgetData(Project $project): array
    {
        $hochrechnung = 0;

        if ((int)$project->notes !== 0 && $project->notes !== null) {
            $hochrechnung = ((int)$this->budgetRepository->getUsedBudget($project->id) * 100) / (int)$project->notes;
        }

        return [
            'gebucht' => money_format('%!n', $this->budgetRepository->getUsedBudget($project->id)),
            'Hochrechnung' => money_format('%!n', $hochrechnung),
            'Deckung' => money_format('%!n', $project->budget - $hochrechnung),
        ];
    }
}
