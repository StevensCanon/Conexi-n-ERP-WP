<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SyncOrdersToErp;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


class ErpController extends Controller
{
    public $tiendaClient;
    public $erpClient;
    public $erpUrl;
    public $tiendaUrl;

    // Constructor para crear e incializar los clientes de Tienda y ERP
    public function __construct(WPTiendaController $tiendaController)
    {
        $this->tiendaClient = $tiendaController->client;
        $this->tiendaUrl = $tiendaController->url;

        $this->erpUrl =  config('services.wp_erp.url');
        $this->erpClient = new Client([
            'auth' => [
                config('services.wp_erp.key'),
                config('services.wp_erp.secret'),
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    // Se envía un pedido al ERP, verificando cada uno de los metodos
    public function sendOrder($id)
    {
        $order = $this->getStoreOrder($id); //Se obtiene el pedido por su id

        if (!$order) {
            return response()->json(['error' => 'El pedido con ID ' . $id . ' no existe.'], 404);
        }

        if ($this->orderExists($id)) {
            return $this->updateOrder($id, $order); //Si verificando en el metodo de orderExists se encuentra con que si existe, loq ue se hara es actualizarlo, con el metodo Update
        } else {
            $response = $this->sendToErp($order);
            return isset($response['error']) ? response()->json($response, 500) : response()->json($response, 201);
        } // de lo contrario se enviara al metodo de sendToEro que es donde se creara nuestro pedido, al menos que no haya un error.
    }

    // Se verifica si un pedido ya existe en el ERP
    public function orderExists($id)
    {
        $fullUrl = $this->tiendaUrl . '/' . $id;

        try {
            $response = $this->erpClient->get($fullUrl);
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return null;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return null;
        }
    }

    // Se obtiene un pedido de la tienda mediante la url de la api del woocommerce de Tienda
    public function getStoreOrder($id)
    {
        $fullUrl = $this->tiendaUrl . '/' . $id;

        try {
            $response = $this->tiendaClient->get($fullUrl);
            $order = json_decode($response->getBody(), true);

            return [
                'status' => $order['status'],
                'payment_method' => $order['payment_method'],
                'payment_method_title' => $order['payment_method_title'],
                'billing' => $order['billing'],
                'shipping' => $order['shipping'],
                'line_items' => $order['line_items'],
                'total' => $order['total']
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return null;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return null;
        }
    }

    /* Se verifica que los campos de line_items esten, de lo contrario no se validara el envía del pedido al ERP, si sale perfecto se creara este mismo pedido. */

    public function sendToErp($orderData)
    {
        if (empty($orderData['line_items'])) {
            return ['error' => 'line_items está vacío o no es válido.'];
        }

        foreach ($orderData['line_items'] as &$item) {
            $item = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
                'total' => $item['total'],
                'price' => $item['price'] ?? $item['total'],
                'name' => $item['name'] ?? 'Producto sin nombre',
            ];
        }

        try {
            $response = $this->erpClient->post($this->erpUrl, [
                'json' => $orderData
            ]);
            Log::info('Respuesta del ERP al enviar el pedido: ', json_decode($response->getBody(), true));
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error al enviar el pedido al ERP: ' . $e->getMessage());
            return ['error' => 'Error al enviar el pedido al ERP: ' . $e->getMessage()];
        }
    }

    // Si existe un pedido, este se actualizara en el ERP
    public function updateOrder($id, $orderData)
    {
        $fullUrl = $this->erpUrl . '/' . $id;

        try {
            $response = $this->erpClient->put($fullUrl, [
                'json' => $orderData
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => 'Error al actualizar el pedido en el ERP: ' . $e->getMessage()];
        }
    }
}
