@extends('layouts.plantilla')

@section('title', 'Crear tienda')

@section('content')
    <div class="max-w-3xl mx-auto my-20 p-6 bg-zinc-800 dark:bg-zinc-800 shadow-lg rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <!-- Título de la tienda -->
            <h1 class="text-2xl font-bold text-white">@lang('Create your Store')</h1>

            <!-- Botón de omitir -->
            @if (Auth::user()->stores->count() == 0)
                <a href="{{ route('dashboard') }}" class="bg-zinc-300 rounded-xl p-2 hover:bg-white text-black inline-block">
                    @lang('Skip')
                </a>
            @endif
        </div>

        @include('partials.validation-errors')

        <form method="POST" enctype="multipart/form-data" action="{{ route('stores.store') }}" class="space-y-4">
            @csrf

            @include('stores._form', ['btnText' => 'Crear'])

        </form>
    </div>
@endsection
