<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveStoreRequest;
use App\Models\Store;
use App\Events\StoreSaved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra todas las tiendas.
     */
    public function index()
    {
        $user = Auth::user();

        return view('stores.index', [
            'newStore' => new Store,
            'stores' => $user->stores()->latest()->paginate(6),
            'deletedStores' => $user->stores()->onlyTrashed()->get(),
        ]);
    }

    /**
     * Muestra el formulario para crear una nueva tienda.
     */
    public function create()
    {
        $this->authorize('create', new Store);

        return view('stores.create', ['store' => new Store]);
    }

    /**
     * Almacena una nueva tienda en la base de datos.
     */
    public function store(SaveStoreRequest $request)
    {

        $user = Auth::user();

        // Crear la tienda asociada al usuario autenticado
        $store = Store::create(array_merge($request->validated(), ['user_id' => $user->id]));

        StoreSaved::dispatch($store);

        return redirect()->route('stores.index')->with('status', 'La tienda fue creada con éxito!');


    }

    /**
     * Muestra los detalles de una tienda.
     */
    public function show(Store $store)
    {
        return view('stores.show', compact('store'));
    }

    /**
     * Muestra el formulario para editar una tienda.
     */
    public function edit(Store $store)
    {
        $this->authorize('update', $store);

        return view('stores.edit', compact('store'));
    }

    /**
     * Actualiza la tienda en la base de datos.
     */
    public function update(Store $store, SaveStoreRequest $request)
    {
        $this->authorize('update', $store);

        // Si se sube una nueva imagen, la procesamos
        if ($request->hasFile('image')) {
            if ($store->image) {
                Storage::delete($store->image); // Eliminar la imagen anterior
            }

            $store->fill($request->validated());
            $store->image = $request->file('image')->store('images');
            $store->save();
        } else {
            $store->update(array_filter($request->validated())); // Actualizamos sin imagen
        }

        StoreSaved::dispatch($store);

        return redirect()->route('stores.show', $store)->with('status', 'El proyecto fue actualizado con éxito!');
    }

    /**
     * Elimina la tienda de la base de datos (soft delete).
     */
    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);

        $store->delete();

        return redirect()->route('stores.index')->with('status', 'El proyecto fue eliminado con éxito!');
    }

    /**
     * Restaura la tienda eliminada de la base de datos.
     */
    public function restore($storeId)
    {
        $store = Store::withTrashed()->findOrFail($storeId);
        $this->authorize('restore', $store);

        $store->restore(); // Restaurar la tienda

        return redirect()->route('stores.index')->with('status', 'El proyecto fue restaurado con éxito!');
    }

    /**
     * Elimina permanentemente la tienda de la base de datos.
     */
    public function forceDelete($storeId)
    {
        $store = Store::withTrashed()->findOrFail($storeId);
        $this->authorize('forceDelete', $store);

        if ($store->image) {
            Storage::delete($store->image);
        }

        $store->forceDelete();

        return redirect()->route('stores.index')->with('status', 'El proyecto fue eliminado permanentemente');
    }

    /**
     * Verifica si el usuario tiene tiendas, si no tiene, lo redirige al formulario para crear una nueva tienda.
     */
    public function continue()
    {
        $user = Auth::user();

        $storeCount = $user->stores()->count();

        if ($storeCount === 0) {
            return view('stores.create', ['store' => new Store]);
        }

        return redirect()->route('dashboard');
    }

}
