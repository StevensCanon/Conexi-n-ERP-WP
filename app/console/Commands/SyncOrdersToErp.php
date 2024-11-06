<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\WPTiendaController;
use App\Http\Controllers\ErpController;
use Illuminate\Support\Facades\DB;

class SyncOrdersToErp extends Command
{
    protected $signature = 'sync:orders';
    protected $description = 'Sincroniza pedidos de la tienda al ERP en lotes';

    protected $tiendaController;
    protected $erpController;

    public function __construct(WPTiendaController $tiendaController, ErpController $erpController)
    {
        parent::__construct();
        $this->tiendaController = $tiendaController;
        $this->erpController = $erpController;
    }


    public function handle()
    {
        // Obtiene todos los pedidos
        $orders = json_decode($this->tiendaController->getOrders()->getContent(), true);

        // Obtiene los IDs de los pedidos ya enviados
        $sentOrderIds = DB::table('sent_orders')->pluck('order_id')->toArray();

        foreach ($orders as $order) {
            // Solo envía pedidos que aún no han sido enviados
            if (!in_array($order['id'], $sentOrderIds)) {
                $this->erpController->sendOrder($order['id']);
                $this->info('Pedido ' . $order['id'] . ' enviado al ERP.');

                // Registra el ID del pedido en la tabla
                DB::table('sent_orders')->insert([
                    'order_id' => $order['id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->info('Pedido ' . $order['id'] . ' ya ha sido enviado al ERP.');
            }
        }
    }
}
