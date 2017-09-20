<?php

namespace App\Repository\Projects;

use BestIt\Harvest\Facade\Harvest;
use BestIt\Harvest\Models\Projects\Projects;
use BestIt\Harvest\Models\Projects\Tasks;
use BestIt\Harvest\Models\Reports\DayEntries;
use DateTime;

/**
 * Class ProjectsDataGenerator
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Repository\Projects
 */
class ProjectsDataRepository
{
    /**
     * Get all projects assigned to clients in harvest.
     *
     * @param $id
     * @return Projects
     */
    public function getProjectsByClientId($id): Projects
    {
        return Harvest::projects()->findByClientId($id);
    }

    /**
     * Get assigned tasks for a given project id.
     *
     * @param $projectId
     * @return Tasks
     */
    public function getAssignedTasksForProjectById($projectId): Tasks
    {
        return Harvest::projects()->tasks($projectId);
    }

    /**
     * Get all time entries added to a project with a very big time range
     * as the harvest api forces us to use those filters.
     *
     * @param $projectId
     * @param $start
     * @param $end
     *
     * @return DayEntries
     */
    public function getAllTimeEntriesByProjectId(
        $projectId,
        $start,
        $end
    ): DayEntries {
        $entries = Harvest::projects()
                          ->report(
                              $projectId,
                              DateTime::createFromFormat('Y-m-d', $start),
                              DateTime::createFromFormat('Y-m-d', $end),
                              true,
                              null,
                              null,
                              null,
                              null,
                              null
                          );

        return $entries;
    }

}
