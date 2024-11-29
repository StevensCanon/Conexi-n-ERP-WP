<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ERP;

class ErpSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $erp;

    /**
     * Crear una nueva instancia del evento.
     *
     * @param  \App\Models\ERP $erp
     * @return void
     */
    public function __construct(ERP $erp)
    {
        $this->erp = $erp;
    }

    /**
     * Obtener los canales en los que el evento debe ser transmitido.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('channel-name'),
        ];
    }
}
