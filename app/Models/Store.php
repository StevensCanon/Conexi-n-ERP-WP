<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;

class Store extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'stores';

    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'nombre_cliente',
        'nit',
        'api_coommerce',
        'clave_key',
        'clave_secret',
        'user_id'
    ];

    public function getApiClient()
    {
        $baseUrl = rtrim($this->api_coommerce, '/');

        return new Client([
            'base_uri' => $baseUrl,
            'auth' => [$this->clave_key, $this->clave_secret],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function erp()
    {
        return $this->hasOne(ERP::class);
    }
}
