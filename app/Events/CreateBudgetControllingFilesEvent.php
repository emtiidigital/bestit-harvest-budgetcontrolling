<?php

namespace App\Events\Clients;

use BestIt\Harvest\Models\Clients\Clients;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class CreateBudgetControllingFilesEvent
 * @author Marcel Thiesies <marcel.thiesies@bestit-online.de>
 * @package App\Events\Clients
 */
class CreateBudgetControllingFilesEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var Clients */
    private $clients;

    /**
     * BudgetControllingCreateFilesEvent constructor.
     *
     * @param Clients $clients
     */
    public function __construct($clients)
    {
        $this->clients = $clients;
    }

    /**
     * Return clients.
     */
    public function getClients(): Clients
    {
        return $this->clients;
    }
}
