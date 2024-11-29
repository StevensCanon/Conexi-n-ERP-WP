<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebCase</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 dark:bg-black overflow-hidden">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-white dark:bg-zinc-900 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center h-16 justify-between">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a class="text-xl text-gray-900 dark:text-white font-bold mx-8" href="{{ route('Home') }}">
                            WebCase
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex space-x-8">
                    <a href="{{ route('login') }}"
                        class="{{ setActive('login') }} text-zinc-400 hover:text-red-600 dark:hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}"
                        class="{{ setActive('register') }} text-zinc-400 hover:text-red-600 dark:hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">{{ __('Register') }}</a>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('Home') }}"
                    class="{{ setActive('Home') }} block text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">{{ __('Home') }}</a>
                <a href="{{ route('login') }}"
                    class="{{ setActive('login') }} block text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">{{ __('Login') }}</a>
                <a href="{{ route('register') }}"
                    class="{{ setActive('register') }} block text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 px-3 py-2 rounded-md text-base font-medium">{{ __('Register') }}</a>
            </div>
        </div>
    </nav>

    <!-- Main Content Section with Black Background -->
    <main class="flex items-center justify-center min-h-screen bg-black text-white">
        <!-- Ensure full height for centering -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Content Section -->
            <div class="mb-16">
                <h1 class="pt-20 text-2xl font-extrabold sm:text-5xl md:text-6xl">
                    Conexión WooCommerce - ERP
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Conecta WooCommerce a tu ERP y gestiona la facturación electrónica de forma rápida y eficiente,
                    ahorrando tiempo y reduciendo errores.
                </p>
                <a href="{{ route('login') }}">

                    <button class="my-10 rounded-xl bg-red-600 hover:bg-red-900 text-white p-4 w-1/6">
                        Comenzar
                    </button>
                </a>
            </div>
        </div>
    </main>

</body>

</html>
