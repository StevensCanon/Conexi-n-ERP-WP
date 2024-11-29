@extends('layouts.plantilla')

@section('title', 'Tienda | ' . $store->nombre)

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Card Principal -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Encabezado -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $store->nombre }}</h2>
                            <p class="mt-1 text-sm text-gray-500">NIT: {{ $store->nit }}</p>
                        </div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $store->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6 space-y-6">
                    <!-- Botones de Acción -->
                    @auth
                        <div class="flex gap-4">
                            @can('update', $store)
                                <a href="{{ route('stores.edit', $store) }}" class="flex-1">
                                    <button
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Editar
                                    </button>
                                </a>
                            @endcan

                            @can('delete', $store)
                                <form method="POST" action="{{ route('stores.destroy', $store) }}" class="flex-1">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Eliminar
                                    </button>
                                </form>
                            @endcan
                        </div>


                        <div class="mt-4 w-full flex items-center ">
                            <a href="{{ route('erp.index', $store) }}"
                                class=" text-center w-full bg-slate-700 hover:bg-slate-800 text-white font-medium py-3 px-6 rounded-lg shadow-lg">
                                Conectar ERP
                            </a>
                        </div>
                    @endauth

                    <!-- Información Detallada -->
                    <div class="bg-gray-50 rounded-lg p-4 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Detalles de la Tienda</h3>
                        <dl class="space-y-3">
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-1/3">Creador:</dt>
                                <dd class="text-sm text-gray-900">Administrador</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-1/3">Cliente:</dt>
                                <dd class="text-sm text-gray-900">{{ $store->nombre_cliente }}</dd>
                            </div>
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-1/3">Categoría:</dt>
                                <dd class="text-sm text-gray-900">
                                    @if ($store->category)
                                        <a href="{{ route('categories.show', $store->category) }}"
                                            class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $store->category->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">No asignada</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex">
                                <dt class="text-sm font-medium text-gray-500 w-1/3">Fecha de Creación:</dt>
                                <dd class="text-sm text-gray-900">{{ $store->created_at->format('d/m/Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('stores.index') }}"
                        class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a la lista de tiendas
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
