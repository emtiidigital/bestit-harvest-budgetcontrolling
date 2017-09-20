<?php

namespace App\Repository\Budget;

use App\Repository\Budget\Calculators\BudgetUsed;

class BudgetDataRepository
{
    /**
     * Get used budget. For calculation we use an
     * external calculator class.
     *
     * @param int $projectId
     * @return int
     */
    public function getUsedBudget(int $projectId): int
    {
        $budgetUsed = new BudgetUsed($projectId);

        return $budgetUsed->get();
    }
}
