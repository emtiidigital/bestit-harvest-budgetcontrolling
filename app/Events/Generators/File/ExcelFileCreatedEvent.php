<?php

namespace App\Events\Generators\File;

use BestIt\Harvest\Models\Clients\Client;

class ExcelFileCreatedEvent
{
    private $client;
    private $data;

    /**
     * ExcelFileCreatedEvent constructor.
     *
     * @param Client $c
     * @param array $d
     */
    public function __construct($c, $d)
    {
        $this->client = $c;
        $this->data = $d;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
