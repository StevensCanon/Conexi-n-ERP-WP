<!-- resources/views/erp/index.blade.php -->

@extends('layouts.plantilla')

@section('title', 'ERPs para la tienda: ' . $store->nombre)

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">ERP para la tienda "{{ $store->nombre }}"</h2>
                    <p class="mt-1 text-sm text-gray-500">NIT: {{ $store->nit }}</p>
                </div>

                <div class="p-6 space-y-6">
                    @if ($erp)
                        <!-- Si ya hay un ERP asociado -->
                        <div class="bg-green-100 p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-green-900">ERP Asociado: {{ $erp->nombre_erp }}</h3>


                            <div class="flex gap-4 mt-4">
                                <!-- Botón para editar -->
                                <a href="{{ route('erp.edit', [$store, $erp]) }}" class="w-1/2">
                                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Editar ERP
                                    </button>
                                </a>

                                <!-- Formulario para eliminar -->
                                <form action="{{ route('erp.destroy', [$store, $erp]) }}" method="POST" class="w-1/2">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Eliminar ERP
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else


                        <!-- Si no hay un ERP asociado -->
                        <div class="bg-gray-100 p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-900">No tienes un ERP asociado.</h3>
                            <p class="text-sm text-gray-600">Puedes crear uno nuevo para integrar tu tienda.</p>

                            <div class="mt-4">
                                <a href="{{ route('erp.create', $store) }}" class="w-full">
                                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Crear ERP
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('stores.show', $store) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                        Volver a la tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
