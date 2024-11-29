@extends('layouts.plantilla')

@section('title', 'Home')
@section('content')

    <div class=" container mx-auto my-20 grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>
            <h1 class="text-4xl font-extrabold text-red-500 mb-4">Hola </h1>
            <p class="text-lg text-gray-300 mb-6">Bienvenid@
                <span class=" text-red-500">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-justify text-zinc-300 mb-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac nisi et velit aliquet ullamcorper.
                Sed vel tellus vel nunc cursus tincidunt. Nullam vitae nulla a purus lacinia luctus. Sed ut aliquam
                elit. Vivamus ut tellus ac lectus euismod bibendum. Integer in ligula non urna sodales auctor ut et
                ligula. Suspendisse potenti.
            </p>
            <p class="text-justify text-zinc-300 mb-4">
                Duis bibendum purus sed erat euismod, vel fermentum dui vehicula. Aliquam erat volutpat. Nunc eu
                nulla vitae lorem scelerisque convallis. Integer euismod mauris non nisi venenatis, at ultrices nunc
                viverra.
                Duis bibendum purus sed erat euismod, vel fermentum dui vehicula. Aliquam erat volutpat. Nunc eu
                nulla vitae lorem scelerisque convallis. Integer euismod mauris non nisi venenatis, at ultrices nunc
                viverra. Duis bibendum purus sed erat euismod, vel fermentum dui vehicula. Aliquam erat volutpat. Nunc eu
                nulla vitae lorem scelerisque convallis. Integer euismod mauris non nisi venenatis, at ultrices nunc
                viverra.
            </p>
        </div>

        <div class="flex justify-center">
            <img src="{{ asset('images/rojo.jpg') }}" alt="Imagen informativa" class="rounded-lg shadow-lg w-full ">
        </div>
    </div>


    </section>



@endsection
