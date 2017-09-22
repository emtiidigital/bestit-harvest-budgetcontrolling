<?php

namespace App\Console\Commands;

use JiraRestApi\Issue\IssueSearchResult;
use JiraRestApi\Issue\IssueService;

class JiraIssuesRepository
{
    private $issueService;

    public function __construct()
    {
        $this->issueService = new IssueService();
    }

    public function getAllStoriesByProjectName($projectName): IssueSearchResult
    {
        return $this->issueService->search(
            sprintf(
                'status != Closed AND issuetype = Epic AND project = "%s"',
                $projectName
            )
        );
    }

    public function getAllEpicsByProjectName($projectName): IssueSearchResult
    {
        return $this->issueService->search(
            sprintf(
                'status != Closed AND issuetype = Story AND project = "%s"',
                $projectName
            )
        );
    }

    public function getAllStoriesByProjectNameAndReporterEmail($projectName, $reporterEmail): IssueSearchResult
    {
        $jql = sprintf(
            'status != Closed AND issueType = Story AND project = "%s" AND reporter = "%s" ORDER BY "Epic Link", Status',
            $projectName,
            $reporterEmail
        );

        return $this->issueService
            ->search(
                $jql,
                $startAt = 0,
                $maxResults = 1000,
                $fields = [],
                $expand = [],
                $validateQuery = true
            );
    }
}
