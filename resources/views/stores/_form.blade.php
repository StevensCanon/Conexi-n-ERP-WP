@csrf

<div class="space-y-2">
    <!-- Nombre de la tienda -->
    <div class="relative">
        <label for="nombre" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('Store Name')
        </label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $store->nombre) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                          focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                          transition-all duration-300 placeholder-zinc-500"
            required placeholder="Nombre de tu tienda">
    </div>

    <!-- NOMBRE CLIENTE -->
    <div class="relative">
        <label for="nombre_cliente" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('Nombre del Cliente')
        </label>
        <input type="text" name="nombre_cliente" id="nombre_cliente"
            value="{{ old('nombre_cliente', $store->nombre_cliente) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                              focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                              transition-all duration-300 placeholder-zinc-500"
            required placeholder="Nombre y Apellido">
    </div>

    <!-- NIT -->
    <div class="relative">
        <label for="nit" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('NIT')
        </label>
        <input type="text" name="nit" id="nit" value="{{ old('nit', $store->nit) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                          focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                          transition-all duration-300 placeholder-zinc-500"
            required placeholder="XXXX-XXXX-XXXX">
    </div>



    <!-- API Coommerce -->
    <div class="relative">
        <label for="api_coommerce" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('API Coommercee')
        </label>
        <input type="text" name="api_coommerce" id="api_coommerce"
            value="{{ old('api_coommerce', $store->api_coommerce) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                          focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                          transition-all duration-300 placeholder-zinc-500"
            required placeholder="https://your_tienda.com/wp-json/wc/v3/orders">
    </div>

    <!-- Clave Key -->
    <div class="relative">
        <label for="clave_key" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('Clave Key')
        </label>
        <input type="text" name="clave_key" id="clave_key" value="{{ old('clave_key', $store->clave_key) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                          focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                          transition-all duration-300 placeholder-zinc-500"
            required placeholder="ck_XXXXXXXXXXXXXXXXXXXXXXX">
    </div>

    <!-- Clave Secret -->
    <div class="relative">
        <label for="clave_secret" class="block text-sm font-semibold text-zinc-300 mb-2">
            @lang('Clave Secret')
        </label>
        <input type="text" name="clave_secret" id="clave_secret"
            value="{{ old('clave_secret', $store->clave_secret) }}"
            class="w-full px-4 py-3 bg-zinc-900 text-white border-2 border-red-500/50 rounded-lg
                          focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                          transition-all duration-300 placeholder-zinc-500"
            required placeholder="cs_XXXXXXXXXXXXXXXXXXXXXXX">
    </div>

    <!-- Botón de Submit -->
    <div class="pt-4">
        <button
            class="w-full py-3 bg-red-500 text-white font-semibold rounded-lg
                         hover:bg-red-600 active:bg-red-700
                         transition-colors duration-200 ease-in-out
                         focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-zinc-800">
            {{ $btnText }}
        </button>
    </div>

    <!-- Link de retorno -->
    @if (Auth::user()->stores->count() > 1)
        <div class="text-center pt-4">
            <a href="{{ route('stores.index') }}"
                class="text-zinc-400 hover:text-white transition-colors duration-200 text-sm">
                Volver a la lista de stores
            </a>
        </div>
    @endif
</div>
