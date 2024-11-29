@extends('layouts.plantilla')

@section('title', 'Bienvenido')

@section('content')
    <div class="max-w-3xl mx-auto my-20 p-6 bg-zinc-200 shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">@lang('Create your Store')</h1>

        @include('partials.validation-errors')

        <form method="POST" enctype="multipart/form-data" action="{{ route('stores.store') }}" class="space-y-4">
            @csrf

            @include('stores._form', ['btnText' => 'Crear'])

            <a href="{{ route('dashboard') }}"></a><button>Omitir</button>


    </div>
    </form>
    </div>
@endsection
