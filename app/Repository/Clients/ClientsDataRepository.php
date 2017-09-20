<?php

namespace App\Repository\Clients;

use BestIt\Harvest\Models\Clients\Clients;
use BestIt\Harvest\Facade\Harvest;

/**
 * Class ClientsDataRepository
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Repository\Clients
 */
class ClientsDataRepository
{
    /**
     * Get all clients without any filter.
     *
     * @return Clients
     */
    public function getAllClients(): Clients
    {
        return Harvest::clients()->all();
    }
}
