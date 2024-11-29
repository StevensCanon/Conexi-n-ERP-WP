<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use Illuminate\Support\Facades\Log;


/**
 * @OA\Info(title="API Usuarios", version="1.0")
 *
 * @OA\Server(url="http://localhost:8000")
 */
class WPTiendaController extends Controller
{
    public $client;
    public $url;
    public $store;

    // Declaro e inicializo las variables para conectarme a mi cliente API, esto es para hacer pruebas, mediante la ruta.
    public function testApiConnection($storeId)
    {
        // Obtener la tienda por ID
        $store = Store::findOrFail($storeId);

        // Obtener el cliente API de la tienda
        $client = $store->getApiClient();

        try {
            // Realizar una solicitud de prueba a la API, por ejemplo, obteniendo datos de productos
            $response = $client->get('orders');  // Ajusta la ruta según lo que quieras consultar de la API
            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Mostrar todos los pedidos",
     *     tags={"Pedidos"},
     *     @OA\Response(
     *         response=200,
     *         description="Retorna todos los pedidos.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="order_number", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="currency", type="string"),
     *                 @OA\Property(property="total", type="number", format="float"),
     *                 @OA\Property(property="line_items", type="array", @OA\Items(type="object"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error al obtener los pedidos."
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Mostrar un pedido específico por ID",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar un pedido específico.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="order_number", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="currency", type="string"),
     *             @OA\Property(property="total", type="number", format="float"),
     *             @OA\Property(property="line_items", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error al obtener el pedido."
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Crear un nuevo pedido",
     *     tags={"Pedidos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"billing", "shipping", "line_items", "total", "subtotal"},
     *             @OA\Property(property="billing", type="object"),
     *             @OA\Property(property="shipping", type="object"),
     *             @OA\Property(property="line_items", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="total", type="number", format="float"),
     *             @OA\Property(property="subtotal", type="number", format="float"),
     *             @OA\Property(property="discount_total", type="number", format="float"),
     *             @OA\Property(property="shipping_total", type="number", format="float"),
     *             @OA\Property(property="payment_method", type="string"),
     *             @OA\Property(property="payment_method_title", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido creado correctamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="order_number", type="string"),
     *             @OA\Property(property="total", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error al crear el pedido."
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Eliminar un pedido",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Pedido eliminado correctamente."
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error al eliminar el pedido."
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/orders/{id}",
     *     summary="Actualizar un pedido",
     *     tags={"Pedidos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"billing", "shipping", "total"},
     *             @OA\Property(property="billing", type="object"),
     *             @OA\Property(property="shipping", type="object"),
     *             @OA\Property(property="total", type="number", format="float"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="payment_method", type="string"),
     *             @OA\Property(property="payment_method_title", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido actualizado correctamente.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="order_number", type="string"),
     *             @OA\Property(property="total", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error al actualizar el pedido."
     *     )
     * )
     */
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
