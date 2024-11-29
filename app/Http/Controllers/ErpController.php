<?php

namespace App\Http\Controllers;

use App\Events\ErpSaved;
use App\Http\Requests\ErpRequest;
use App\Models\ERP;
use App\Models\Store;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


class ErpController extends Controller
{
    use AuthorizesRequests;


    public $tiendaClient;
    public $erpClient;
    public $erpUrl;
    public $tiendaUrl;

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

    public function index(Store $store)
    {
        // Recuperamos el ERP asociado a la tienda
        $erp = $store->erp;  // Relación definida en el modelo Store

        return view('erp.index', compact('store', 'erp'));
    }


    public function create(Store $store)
    {
        return view('erp.create', compact('store'));
    }

    // Método para guardar los datos del ERP
    public function store(ErpRequest $request, Store $store)
    {
        // Crear un nuevo ERP asociado a la tienda
        $erp = new Erp([
            'nombre_erp' => $request->erp,
            'email'      => $request->email,
            'token'  => $request->token,
            'store_id'   => $store->id,  // Asociamos la tienda
        ]);
        $erp->save();

        // Redirigir a la vista index de ERP o la página de la tienda
        return redirect()->route('stores.show', $store->id)->with('success', 'ERP creado exitosamente');
    }

    public function edit(Store $store, Erp $erp)
    {
        // Verificamos si el ERP pertenece a la tienda
        if ($erp->store_id !== $store->id) {
            return redirect()->route('stores.show', $store->id)->with('error', 'Este ERP no pertenece a la tienda seleccionada.');
        }

        return view('erp.edit', compact('erp', 'store'));
    }

    // Actualizar un ERP en la base de datos
    public function update(ErpRequest $request, Store $store, Erp $erp)
    {
        // Verificamos si el ERP pertenece a la tienda
        if ($erp->store_id !== $store->id) {
            return redirect()->route('stores.show', $store->id)->with('error', 'Este ERP no pertenece a la tienda seleccionada.');
        }

        // Actualizamos los datos del ERP
        $erp->update([
            'nombre_erp' => $request->erp,
            'email' => $request->email,
            'token'  => $request->token,
        ]);

        // Redirigimos a la página de la tienda con un mensaje de éxito
        return redirect()->route('stores.show', $store->id)->with('success', 'ERP actualizado exitosamente');
    }

    // Eliminar un ERP
    public function destroy(Store $store, Erp $erp)
    {
        // Verificamos si el ERP pertenece a la tienda
        if ($erp->store_id !== $store->id) {
            return redirect()->route('stores.show', $store->id)->with('error', 'Este ERP no pertenece a la tienda seleccionada.');
        }

        // Eliminar el ERP
        $erp->delete();

        // Redirigir a la vista de la tienda con un mensaje de éxito
        return redirect()->route('stores.show', $store->id)->with('success', 'ERP eliminado exitosamente');
    }

    public function postToErp(Request $request, $erpId)
    {
        // Buscar el ERP por su ID
        $erp = Erp::find($erpId);

        if (!$erp) {
            return response()->json(['error' => 'ERP no encontrado'], 404);
        }

        try {
            // Obtener el cliente configurado
            $client = $erp->getApiClient();

            // Datos de la solicitud (puedes ajustarlos según tus necesidades)
            $data = $request->all();

            // Hacer la solicitud POST al endpoint deseado
            $response = $client->post('purchase-orders', [
                'json' => $data, // Datos de la solicitud en formato JSON
            ]);

            // Decodificar la respuesta y devolverla
            $responseBody = json_decode($response->getBody(), true);

            return response()->json(['success' => true, 'data' => $responseBody]);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testConnection($erpId)
    {
        // Buscar el ERP por su ID
        $erp = Erp::find($erpId);

        if (!$erp) {
            return response()->json(['error' => 'ERP no encontrado'], 404);
        }

        try {
            // Obtener el cliente configurado
            $client = $erp->getApiClient();

            // Hacer la solicitud POST sin datos (solo para probar la conexión)
            $response = $client->post('purchase-orders', [
                // No enviamos datos en esta solicitud, solo queremos verificar la conexión
            ]);

            // Verificar el código de estado de la respuesta para determinar si la conexión fue exitosa
            if ($response->getStatusCode() == 200) {
                return response()->json(['success' => true, 'message' => 'Conexión exitosa con la API del ERP.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Error al conectar con la API del ERP.']);
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Capturar error detallado y devolverlo
            return response()->json([
                'error' => 'Error al realizar la solicitud: ' . $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body'
            ], 500);
        }
    }

    // Para pausar el comando, mediante un botón.
    public function togglePause($storeId)
    {
        $erp = Erp::where('store_id', $storeId)->first();

        if ($erp) {
            $erp->sync_paused = !$erp->sync_paused;
            $erp->save();

            return back()->with('status', $erp->sync_paused ? 'Sincronización pausada.' : 'Sincronización reanudada.');
        }

        return back()->with('error', 'No se encontró el ERP para esta tienda.');
    }
}
