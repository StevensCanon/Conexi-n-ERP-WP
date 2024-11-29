@extends('layouts.plantilla')

@section('title', 'Store')
@section('content')

    <div class="container mx-auto my-20">
        <div class="flex justify-between items-center mb-8">
            <!-- Mostrar nombre de la categoría si está presente -->
            @isset($category)
                <div>
                    <h1 class="font-bold text-3xl text-sky-400">{{ $category->name }}</h1>
                    <p class="text-sky-400 underline mt-4">
                        <a href="{{ route('stores.index', $category) }}">Volver a la tienda</a>
                    </p>
                </div>
            @else
                <!-- Título de la tienda si no hay categoría -->
                <h1 class="font-extrabold text-3xl text-red-500">@lang('Stores')</h1>

                <!-- Botón de crear tienda solo si el usuario es administrador -->
                @if (Auth::user()->role === 'admin')
                    @can('create', $newStore)
                        <a href="{{ route('stores.create') }}">
                            <button class="bg-white hover:bg-zinc-200 rounded-lg text-black font-bold py-2 px-4">
                                Crear
                            </button>
                        </a>
                    @endcan
                @endif
            @endisset
        </div>

        <!-- Descripción introductoria -->
        <p class="text-justify text-gray-300 mb-10">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
            pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
            laborum.
        </p>

        <!-- Mostrar las tiendas activas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($stores as $store)
                <div class="max-w-sm bg-zinc-200 border border-zinc-200 rounded-lg shadow-lg">
                    @if ($store->image)
                        <img class="w-full h-48 object-cover rounded-t-lg" src="{{ asset('storage/' . $store->image) }}"
                            alt="{{ $store->nombre }}">
                    @endif

                    <div class="p-6">
                        <h1 class="mb-2 text-2xl font-bold text-slate-900">{{ $store->nombre }}</h1>
                        <h2 class="mb-2 font-semibold text-slate-900">{{ $store->nombre_cliente }}</h2>
                        <p class="mb-3 text-gray-700">NIT: {{ $store->nit }}</p>

                        <!-- Aquí se coloca la fecha en una línea separada -->
                        <span class="block text-sm text-slate-500  rounded py-1">
                            {{ $store->created_at->diffForHumans() }}
                        </span>

                        <!-- Botones de acción (en la misma línea) -->
                        <div class="flex items-center mt-4 space-x-4">
                            <!-- Botón de "Ver Tienda" -->
                            <a href="{{ route('stores.show', $store) }}"
                                class="inline-flex w-auto px-4 py-2 text-sm font-medium text-white bg-sky-950 rounded-lg hover:bg-gray-700">
                                Ver Tienda
                            </a>

                            <!-- Botón de "Pausar/Reanudar Sincronización" -->
                            @if ($store->erp)
                                <form action="{{ route('erp.togglePause', $store->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="mt-4 py-2 px-4 text-sm font-medium text-white rounded-lg hover:bg-red-800
                                       {{ $store->erp->sync_paused ? 'bg-orange-400' : 'bg-red-500' }}">
                                        @if ($store->erp->sync_paused)
                                            Reanudar Sincronización
                                        @else
                                            Pausar Sincronización
                                        @endif
                                    </button>
                                </form>
                            @else
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botones de Paginación -->
        <div class="mt-8">
            {{ $stores->links('pagination::tailwind') }} <!-- Utiliza el diseño de paginación de Tailwind -->
        </div>

        <!-- Mostrar las tiendas eliminadas -->
        @if ($deletedStores->isNotEmpty())
            <h2 class="text-2xl font-bold text-red-600 mt-60">Tienda(s) eliminada(s)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @foreach ($deletedStores as $deletedStore)
                    <div class="max-w-sm bg-zinc-200 border border-zinc-200 rounded-lg shadow-lg">
                        <div class="p-6">
                            <h1 class="mb-2 text-4xl font-bold text-slate-900">{{ $deletedStore->nombre }}</h1>
                            <h2 class="mb-2 font-semibold text-slate-900">{{ $deletedStore->nombre_cliente }}</h2>
                            <p class="mb-3 text-gray-700">NIT: {{ $deletedStore->nit }}</p>

                            <span class="block text-sm text-slate-500  rounded py-1">
                                {{ $deletedStore->created_at->diffForHumans() }}
                            </span>

                            <!-- Botón para restaurar la tienda -->
                            <form action="{{ route('stores.restore', $deletedStore->id) }}" method="POST" class="mt-4">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                    Restaurar Tienda
                                </button>
                            </form>

                            <!-- Botón para eliminar permanentemente la tienda -->
                            <form action="{{ route('stores.forceDelete', $deletedStore->id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-800">
                                    Eliminar Permanentemente
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
