<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::first();


        Store::create([
            'nombre' => 'WP - TIENDA',
            'nombre_cliente' => 'Tomate Agencia Digital',
            'nit' => '1234-5678-9012',
            'api_coommerce' => ' https://tomateagenciadigital.com/WpTienda/wp-json/wc/v3/orders',
            'clave_key' => Crypt::encryptString('ck_671bdde1a36968328a1248efcaec10586d3c5dc5'),
            'clave_secret' => Crypt::encryptString('cs_853a06940d744a15d7a0baa4308e73515b4229c1'),
            'user_id' => $user->id,
        ]);

        Store::create([
            'nombre' => 'Tienda Real Madrid',
            'nombre_cliente' => 'Florentino Perez',
            'nit' => '0987654321',
            'api_coommerce' => 'https://api.example.com',
            'clave_key' => Crypt::encryptString('clave_secreta'),
            'clave_secret' => Crypt::encryptString('clave_super_secreta'),
            'user_id' => $user->id
        ]);

        Store::create([
            'nombre' => 'Tienda AutoGarage',
            'nombre_cliente' => 'Claudio Rodriguez',
            'nit' => '1122334455',
            'api_coommerce' => 'https://api.example2.com',
            'clave_key' => Crypt::encryptString('clave_secreta_2'),
            'clave_secret' => Crypt::encryptString('clave_super_secreta_2'),
            'user_id' => $user->id,
        ]);
    }
}
