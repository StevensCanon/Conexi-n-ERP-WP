@extends('layouts.plantilla')

@section('title', 'Editar tienda')

@section('content')

    @include('partials.validation-errors')

    <div class="max-w-3xl mx-auto my-20 p-6 bg-zinc-800 shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-white mb-6">@lang('Edit your Store')</h1>

        <form method="POST" enctype="multipart/form-data" action="{{ route('stores.update', $store) }}">
            @method('PATCH')

            @include('stores._form', ['btnText' => 'Actualizar'])

        </form>

    @endsection
