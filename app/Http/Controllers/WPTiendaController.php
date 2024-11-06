<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WPTiendaController extends Controller
{
    public $client;
    public $url;


    //Declaro e inicializo las variables para conectarme a mi cliente api.
    public function __construct()
    {
        $this->url = config('services.wp_tienda.url');

        $this->client = new Client([
            'auth' => [
                config('services.wp_tienda.key'),
                config('services.wp_tienda.secret'),
            ],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    // Funcion para obtener todos los pedidos
    public function getOrders()
    {
        try {
            $response = $this->client->get($this->url);
            $data = json_decode($response->getBody(), true);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los pedidos: ' . $e->getMessage()], 500);
        }
    }

    // Funcion para obtener un pedido específico por ID
    public function getOrder($id)
    {
        try {
            $fullUrl = $this->url . '/' . $id;
            $response = $this->client->get($fullUrl);
            $order = json_decode($response->getBody(), true);
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el pedido: ' . $e->getMessage()], 500);
        }
    }

    // Funcion para crear un nuevo pedido, con validaciones
    public function createOrder(Request $request)
    {
        $request->validate([
            'billing' => 'required|array',
            'shipping' => 'required|array',
            'line_items' => 'required|array',
            'total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'discount_total' => 'nullable|numeric',
            'shipping_total' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'payment_method_title' => 'nullable|string',
        ]);

        $orderData = [
            'status' => 'processing',
            'payment_method' => $request->input('payment_method', 'cod'),
            'payment_method_title' => $request->input('payment_method_title', 'Contra reembolso'),
            'billing' => $request->input('billing'),
            'shipping' => $request->input('shipping'),
            'line_items' => $request->input('line_items'),
            'total' => $request->input('total'),
            'subtotal' => $request->input('subtotal'),
            'discount_total' => $request->input('discount_total', 0),
            'shipping_total' => $request->input('shipping_total', 0),
        ];

        try {
            $response = $this->client->post($this->url, ['json' => $orderData]);
            $createdOrder = json_decode($response->getBody(), true);
            return response()->json($createdOrder, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el pedido: ' . $e->getMessage()], 500);
        }
    }

    // Funcion para eliminar un pedido por ID
    public function deleteOrder($id)
    {
        try {
            $fullUrl = $this->url . '/' . $id;
            $this->client->delete($fullUrl);
            return response()->json(['message' => 'Pedido eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el pedido: ' . $e->getMessage()], 500);
        }
    }

    // Funcion para actualizar un pedido existente
    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'total' => 'required|numeric',
            'billing' => 'required|array',
            'shipping' => 'required|array',
        ]);

        $orderData = [
            'status' => $request->input('status', 'processing'),
            'payment_method' => $request->input('payment_method', 'cod'),
            'payment_method_title' => $request->input('payment_method_title', 'Contra reembolso'),
            'total' => $request->input('total'),
            'billing' => $request->input('billing'),
            'shipping' => $request->input('shipping'),
        ];

        try {
            $fullUrl = $this->url . '/' . $id;
            $response = $this->client->put($fullUrl, ['json' => $orderData]);
            $updatedOrder = json_decode($response->getBody(), true);
            return response()->json($updatedOrder);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el pedido: ' . $e->getMessage()], 500);
        }
    }
}
