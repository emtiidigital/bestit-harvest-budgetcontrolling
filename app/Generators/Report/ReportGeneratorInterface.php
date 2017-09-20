<?php

namespace App\Generators\Report;

interface ReportGeneratorInterface
{
    public function process(): bool;
}
