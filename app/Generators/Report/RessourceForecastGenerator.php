<?php

namespace App\Generators\Report;

use App\Generators\File\ExcelFileGenerator;
use JiraRestApi\Issue\Issue;

class RessourceForecastGenerator implements ReportGeneratorInterface
{
    private $issues;
    private $filename;

    /**
     * RessourceForecastGenerator constructor.
     *
     * @param Issue[] $issues
     * @param $filename
     */
    public function __construct($issues, $filename)
    {
        $this->issues = $issues;
        $this->filename = $filename;
    }

    public function process(): bool
    {
        $data = [];

        /** @var Issue $issue */
        foreach ($this->issues as $issue) {
            $issue->fields->getCustomFields();
            $data[] = $this->getIssueDataForReport($issue);
        }

        $generator = new ExcelFileGenerator();
        $generator->setFileName($this->filename);
        $generator->setFileData($data);
        $generator->generate();

        return true;
    }

    private function getIssueDataForReport($issue)
    {
        $estimates = $this->getEstimatesFromCustomFieldsFromIssue($issue);

        return [
            'KEY' => $this->getIssueKeyFromIssue($issue),
            'SUMMARY' => $this->getSummaryFromIssue($issue),
            'STATUS NAME' => $this->getIssueStatusFromIssue($issue),
            'AUFWAND IN EUR' => '',
            'BACKEND PT' => $estimates['backend'] ?? '',
            'FRONTEND PT' => $estimates['frontend'] ?? '',
            'PM & KONZEPTION PT' => $estimates['pm_konzeption'] ?? '',
            'QA PT' => $estimates['qa'] ?? '',
            'BERATUNG PT' => $estimates['beratung'] ?? '',
            'DESIGN PT' => $estimates['design'] ?? '',
            'CONTENT PT' => $estimates['content'] ?? ''
        ];
    }

    private function getIssueKeyFromIssue($issue)
    {
        return $issue->key;
    }

    private function getSummaryFromIssue($issue)
    {
        return $issue->fields->summary;
    }

    private function getIssueStatusFromIssue($issue)
    {
        $data = $issue->fields->status;

        return $data->name ?? '';
    }

    private function getEstimatesFromCustomFieldsFromIssue($issue)
    {
        $estimates = [];

        $customFields = $issue->fields->getCustomFields();

        foreach ($customFields as $key => $value) {
            // Backend
            if ($key === 'customfield_11906') {
                $estimates['backend'] = $value->value ?? '';
            }

            // Frontend
            if ($key === 'customfield_11905') {
                $estimates['frontend'] = $value->value ?? '';
            }

            // PM & Konzeption
            if ($key === 'customfield_11907') {
                $estimates['pm_konzeption'] = $value->value ?? '';
            }

            // QA
            if ($key === 'customfield_11918') {
                $estimates['qa'] = $value->value ?? '';
            }

            // Beratung
            if ($key === 'customfield_11919') {
                $estimates['beratung'] = $value->value ?? '';
            }

            // Design
            if ($key === 'customfield_11908') {
                $estimates['design'] = $value->value ?? '';
            }

            // Content
            if ($key === 'customfield_11920') {
                $estimates['content'] = $value->value ?? '';
            }
        }

        return $estimates;
    }
}
