<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContinueController extends Controller
{
    use AuthorizesRequests;

    // Función que se implemento con el fin de que cuando se registre un nuevo usuario, sea redirigido al formulario de crear una tienda, solo si asi lo desea, de lo contrario puede omitir, este paso.
    public function index()
    {
        $this->authorize('create', $store = new Store);

        return view('stores.create', [
            'store' => $store,
        ]);
    }
}
