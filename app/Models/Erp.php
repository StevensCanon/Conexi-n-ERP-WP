<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Store;  // Aquí está la importación correcta
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


class Erp extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = true;

    protected $fillable = [
        'nombre_erp',
        'email',
        'token',
        'store_id'
    ];

    /**
     * Genera un cliente HTTP configurado para interactuar con el ERP.
     *
     * @return Client
     */
    public function getApiClient()
    {

        $email = $this->email;
        $token = $this->token;

        // Concatenar correo y token y codificar en base64
        $auth = "$email:$token";
        $clave_b64 = base64_encode($auth);


        return new Client([
            'base_uri' => 'https://api.alegra.com/api/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Basic $clave_b64",
                'Content-Type' => 'application/json',
            ],
        ]);
    }


    // Crear una orden, esto tiene que ver, con el tema de allegra, revisar documentacion, para entenderlo de mejor manera
    public function createPurchaseOrder($orderData)
    {
        try {
            // Obtiene el cliente API ya configurado
            $client = $this->getApiClient();

            // Realiza la solicitud POST para crear la orden de compra
            $response = $client->post('purchase-orders', [
                'json' => $orderData // El cuerpo del pedido en formato JSON
            ]);

            // Si la respuesta es exitosa, puedes procesar la respuesta
            $body = $response->getBody();
            $data = json_decode($body);

            return $data; // Devuelve la respuesta de la API, puedes procesarla según tus necesidades
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Si ocurre un error, captura el error y muestra detalles
            $response = $e->getResponse();
            $errorBody = $response ? $response->getBody()->getContents() : 'No response body';
            $this->error("Error al crear la orden de compra: $errorBody");
            return null;
        }
    }


    // Relación con el modelo Store (tiendas)
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
