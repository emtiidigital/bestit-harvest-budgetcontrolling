<?php

namespace App\Repository\Budget\Calculators;

use App\Repository\Projects\ProjectsDataRepository;
use BestIt\Harvest\Models\Projects\Tasks;
use BestIt\Harvest\Models\Reports\DayEntries;
use BestIt\Harvest\Models\Reports\DayEntry;

/**
 * Class BudgetUsed
 * @package App\Repository\Budget\Calculators
 */
class BudgetUsed
{
    private $projectId;
    private $projectRepository;

    /**
     * BudgetUsed constructor.
     *
     * @param int $projectId
     */
    public function __construct(
        $projectId
    ) {
        $this->projectId = $projectId;
        $this->projectRepository = new ProjectsDataRepository();
    }

    /**
     * @return int
     */
    public function get(): int
    {
        $timeEntries = $this->projectRepository
            ->getAllTimeEntriesByProjectId(
                $this->getProjectId(),
                '2017-01-01',
                '2099-12-31'
            );

        $projectTasks = $this->projectRepository
            ->getAssignedTasksForProjectById(
                $this->getProjectId()
            );

        return $this->calculateUsedBudget($timeEntries, $projectTasks);
    }

    /**
     * Calculate used budget by calculating:
     *      (hours of time entry * task rate)
     *
     * But first we need to find the correct task, as tasks are assigned to
     * projects where they can contain another hourly rate than default.
     *
     * @param DayEntries $timeEntries
     * @param Tasks $projectTasks
     *
     * @return int
     */
    private function calculateUsedBudget(DayEntries $timeEntries, Tasks $projectTasks): int
    {
        $usedBudget = 0;

        if ($timeEntries) {
            /** @var DayEntry $timeEntry */
            foreach ($timeEntries as $timeEntry) {
                $taskRate = $this->findTaskRateFromAssignedTasksToProject(
                    $projectTasks,
                    $timeEntry->taskId
                );

                $usedBudget += ($timeEntry->hours * $taskRate);
            }
        }

        return $usedBudget;
    }

    /**
     * We need to find the hourly task rate for assigned tasks to a project
     * by used task id in time entry so we are able to calculate used budget.
     *
     * @param $tasks
     * @param $taskId
     * @return int
     */
    private function findTaskRateFromAssignedTasksToProject(Tasks $tasks, $taskId): int
    {
        foreach ($tasks as $task) {
            if ($task->taskId === $taskId) {
                return $task->hourlyRate;
            }
        }

        return 120;
    }

    /**
     * Get project id.
     *
     * @return int
     */
    private function getProjectId(): int
    {
        return (int) $this->projectId;
    }
}
