<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\RequestException;

class SyncOrdersToErp extends Command
{
    protected $signature = 'sync:orders';
    protected $description = 'Sincroniza pedidos de la tienda al ERP en lotes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $stores = Store::all();

        foreach ($stores as $store) {
            if ($store->erp) {
                if ($store->erp->sync_paused) {
                    $this->info('La sincronización está pausada para la tienda ' . $store->name);
                    continue;
                }

                try {

                    $tiendaClient = $store->getApiClient(); // Se obtiene el cliente de la tienda
                    $erpClient = $store->erp->getApiClient(); // Cliente de ERP

                    // Obtén todos los pedidos de la tienda
                    $response = $tiendaClient->get('orders');
                    $orders = json_decode($response->getBody(), true);

                    // Verifica si la respuesta de los pedidos es válida
                    if (!$orders) {
                        $this->error('No se pudo obtener los pedidos de la tienda ' . $store->name);
                        continue;  // Continúa con la siguiente tienda si no hay pedidos
                    }

                    // Obtiene los IDs de los pedidos ya enviados
                    $sentOrderIds = DB::table('sent_orders')->pluck('order_id')->toArray();

                    foreach ($orders as $order) {
                        // Solo envía pedidos que aún no han sido enviados
                        if (!in_array($order['id'], $sentOrderIds)) {
                            // Enviar el pedido al ERP
                            $erpResponse = $erpClient->post('orders', [
                                'json' => $order,  // Suponiendo que se envían como JSON
                            ]);

                            // Verifica si la respuesta del ERP es exitosa
                            if ($erpResponse->getStatusCode() == 201) {
                                $this->info('Pedido ' . $order['id'] . ' enviado al ERP.');

                                // Registra el ID del pedido en la tabla
                                DB::table('sent_orders')->insert([
                                    'order_id' => $order['id'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            } else {
                                $this->error('Error al enviar el pedido ' . $order['id'] . ' al ERP.');
                            }
                        } else {
                            $this->info('Pedido ' . $order['id'] . ' ya ha sido enviado al ERP.');
                        }
                    }
                } catch (RequestException $e) {
                    // Maneja cualquier excepción que ocurra en las solicitudes API
                    $this->error('Error al conectar con la tienda ' . $store->name . ': ' . $e->getMessage());
                }
            } else {
                $this->info('La tienda ' . $store->name . ' no tiene ERP asociado.');
            }
        }
    }
}
