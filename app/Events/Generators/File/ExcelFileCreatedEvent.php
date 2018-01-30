<?php

namespace App\Events\Generators\File;

class ExcelFileCreatedEvent
{
    /** @var string $fileName */
    private $fileName;

    public function __construct(
        $fileName
    ) {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}
