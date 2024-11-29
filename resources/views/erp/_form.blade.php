<div class="space-y-4">
    <!-- Campo de selección de ERP -->
    <div class="relative">
        <label for="erp" class="block text-sm font-semibold text-black mb-2">
            @lang('Selecciona tu ERP')
        </label>
        <select name="erp" id="erp" required
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="Allegra" {{ old('erp', $erp->nombre_erp ?? '') == 'Allegra' ? 'selected' : '' }}>Allegra
            </option>
            <option value="Odoo" {{ old('erp', $erp->nombre_erp ?? '') == 'Odoo' ? 'selected' : '' }}>Odoo</option>
            <option value="SAP" {{ old('erp', $erp->nombre_erp ?? '') == 'SAP' ? 'selected' : '' }}>SAP</option>
            <option value="Microsoft Dynamics"
                {{ old('erp', $erp->nombre_erp ?? '') == 'Microsoft Dynamics' ? 'selected' : '' }}>Microsoft Dynamics
            </option>
            <option value="Zoho ERP" {{ old('erp', $erp->nombre_erp ?? '') == 'Zoho ERP' ? 'selected' : '' }}>Zoho ERP
            </option>
        </select>
        @error('erp')
            <div class="text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <!-- Campo para ingresar el email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $erp->email ?? '') }}"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            required>
        @error('email')
            <div class="text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <!-- Campo para ingresar el Token Base64 -->
    <div>
        <label for="token" class="block text-sm font-medium text-gray-700">Token</label>
        <input type="text" name="token" id="token" value="{{ old('token', $erp->token ?? '') }}"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            required>
        @error('token')
            <div class="text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
        @if (isset($erp))
            Actualizar ERP
        @else
            Crear ERP
        @endif
    </button>
</div>
