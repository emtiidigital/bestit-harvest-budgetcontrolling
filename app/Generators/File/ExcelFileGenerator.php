<?php

namespace App\Generators\File;

use App\Events\Generators\File\ExcelFileCreatedEvent;
use BestIt\Harvest\Models\Clients\Client;
use Excel;

/**
 * Class ExcelFileGenerator
 * @package App\Generators\File
 */
class ExcelFileGenerator implements FileGeneratorInterface
{
    /** @var $client */
    private $client;
    /** @var $data */
    private $data;

    /**
     * Create excel file for every customer with prepared data from harvest api.
     *
     * @return bool
     */
    public function process(): bool
    {
        $c = $this->getClient();
        $d = $this->getData();

        info('Create Excel file for customer: ' . $c->name);

        $filename = $c->id . ' - ' . $c->name;

        Excel::create($filename, function ($excel) use ($d) {
            $excel->sheet('Controlling', function ($sheet) use ($d) {
                $sheet->fromArray($d, null, 'A1', true);
            });
        })->store('xls');

        event(new ExcelFileCreatedEvent($c, $d));

        return true;
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
