<?php

namespace App\Generators\File;

use App\Events\Generators\File\ExcelFileCreatedEvent;
use Excel;

/**
 * Class ExcelFileGenerator
 * @package App\Generators\File
 */
class ExcelFileGenerator implements FileGeneratorInterface
{
    const EXCEL_FILE_TYPE = 'xls';

    private $fileNamePrefix;
    private $fileNameSuffix;
    private $fileName;
    private $fileData;

    public function generate(): bool
    {
        $data = $this->getFileData();
        $name = sprintf(
            '%s - %s - %s',
            $this->getFileNamePrefix() ?? 'Report for',
            $this->getFileName(),
            $this->getFileNameSuffix() ?? 'generated'
        );

        info('Create Excel File: ' . $name);
        Excel::create($name, function ($excel) use ($data) {

            $excel->sheet('Controlling', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', true);

                $sheet->row(1, function ($row) {
                    $row->setBackground('#000000');
                });

                $sheet->cells('A1:H1', function ($cells) {
                    $cells->setFontColor('#ffffff');
                });
            });
        })->store(self::EXCEL_FILE_TYPE);

        $fqfn = $name . '.' .self::EXCEL_FILE_TYPE;
        event(new ExcelFileCreatedEvent($fqfn));

        return true;
    }

    public function setFileNamePrefix($fileNamePrefix)
    {
        $this->fileNamePrefix = $fileNamePrefix;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setFileData($fileData)
    {
        $this->fileData = $fileData;
    }

    private function getFileName()
    {
        return $this->fileName;
    }

    public function getFileData()
    {
        return $this->fileData;
    }

    public function getFileNamePrefix()
    {
        return $this->fileNamePrefix;
    }

    public function getFileNameSuffix()
    {
        return $this->fileNameSuffix;
    }

    public function setFileNameSuffix($fileNameSuffix)
    {
        $this->fileNameSuffix = $fileNameSuffix;
    }
}
