{{-- Este codigo se usa para traer todos los errores de validacion --}} 

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-300 border border-red-300 text-red-800 rounded-lg" role="alert">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
