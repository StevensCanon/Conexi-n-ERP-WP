@extends('layouts.plantilla')

@section('title', 'Editar ERP para la tienda: ' . $store->nombre)

@section('content')
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900">Editar ERP para la tienda "{{ $store->nombre }}"</h2>
                </div>

                <div class="p-6 space-y-6">
                    <form action="{{ route('erp.update', [$store, $erp]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('erp._form', ['erp' => $erp])
                    </form>
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
