<?php

namespace App\Generators\File;

interface FileGeneratorInterface
{
    public function process(): bool;
}
