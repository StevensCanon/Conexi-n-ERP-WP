<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebCase</title>
    @vite('resources/css/app.css')
</head>
<body>
    <h1>Error 404</h1>
    <main class="flex items-center justify-center min-h-screen bg-black text-white">
        <!-- Ensure full height for centering -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Content Section -->
            <div class="mb-16">
                <h1 class="pt-20 text-2xl font-extrabold sm:text-5xl md:text-6xl">
                    Error 404
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Pagina no encontrada
                </p>
                <a href="{{ route('Home') }}">

                    <button class="my-10 rounded-xl bg-red-600 hover:bg-red-900 text-white p-4 w-auto">
                        Volver
                    </button>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
