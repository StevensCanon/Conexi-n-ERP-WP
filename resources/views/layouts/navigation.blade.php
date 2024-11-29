@php
    use Illuminate\Support\Facades\Auth;
@endphp

@auth
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const nav = document.getElementById("sidebar-nav");
            const textElements = document.querySelectorAll(".nav-text");
            const userButton = document.getElementById("user-menu-button");
            const userMenu = document.getElementById("user-menu");
            const chevronIcon = document.getElementById("chevron-icon");

            // Función para manejar la visibilidad del texto
            const toggleTextVisibility = (show) => {
                textElements.forEach(el => {
                    if (show) {
                        el.classList.remove('hidden');
                        setTimeout(() => el.classList.remove('opacity-0'), 50);
                    } else {
                        el.classList.add('opacity-0');
                        setTimeout(() => el.classList.add('hidden'), 200);
                    }
                });
            };

            // Manejo del hover del navbar
            nav.addEventListener("mouseenter", function() {
                nav.classList.remove("w-16");
                nav.classList.add("w-45");
                toggleTextVisibility(true);
            });

            nav.addEventListener("mouseleave", function() {
                nav.classList.remove("w-64");
                nav.classList.add("w-16");
                toggleTextVisibility(false);
                userMenu.classList.add("hidden");
                chevronIcon.classList.remove("rotate-180");
            });

            // Manejo del menú de usuario
            userButton.addEventListener("click", function(e) {
                e.stopPropagation();
                const isOpen = userMenu.classList.contains("hidden");
                if (isOpen) {
                    userMenu.classList.remove("hidden");
                    chevronIcon.classList.add("rotate-180");
                } else {
                    userMenu.classList.add("hidden");
                    chevronIcon.classList.remove("rotate-180");
                }
            });

            // Inicializar estado
            toggleTextVisibility(false);

            // Cerrar el dropdown si se hace clic fuera
            document.addEventListener("click", function(event) {
                if (!userButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add("hidden");
                    chevronIcon.classList.remove("rotate-180");
                }
            });
        });
    </script>

    <nav id="sidebar-nav"
        class="bg-white dark:bg-zinc-900 border-l border-gray-200 dark:border-zinc-800 fixed top-0 right-0 h-screen w-16 transition-all duration-300">
        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="p-4 flex items-center justify-center border-b border-gray-200 dark:border-zinc-800">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-zinc-700 rounded-lg flex items-center justify-center shrink-0">
                        <span class="text-white font-bold text-lg">W</span>
                    </div>
                    <span
                        class="nav-text text-gray-200 dark:text-white font-semibold text-lg transition-all duration-200 opacity-0 hidden">
                        WP-ERP
                    </span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <!-- Dashboard Link -->
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors {{ request()->routeIs('dashboard') ? 'active-nav-link' : 'inactive-nav-link' }}">
                    <x-heroicon-s-home class="w-6 h-6 shrink-0 group-hover:text-zinc-200 dark:group-hover:text-zinc-200" />
                    <span class="nav-text ml-3 transition-all duration-200 ">
                        {{ __('Inicio') }}
                    </span>
                </a>

                <!-- Stores Link -->
                <a href="{{ route('stores.index') }}"
                    class="group flex items-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors {{ request()->routeIs('stores.index') ? 'active-nav-link' : 'inactive-nav-link' }}">
                    <x-bxs-store class="w-6 h-6 shrink-0 group-hover:text-zinc-200 dark:group-hover:text-zinc-200" />
                    <span class="nav-text ml-3 transition-all duration-200 opacity-0 hidden">
                        {{ __('Tiendas') }}
                    </span>
                </a>

            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                <div class="relative flex justify-center">
                    <button id="user-menu-button"
                        class="w-full flex items-center justify-center p-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        <div class="flex items-center justify-center">
                            <x-heroicon-s-user class="w-8 h-8 text-zinc-200 dark:text-zinc-200 shrink-0" />
                            <div class="nav-text ml-3 transition-all duration-200 opacity-0 hidden">
                                <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <x-heroicon-m-chevron-up id="chevron-icon"
                                class="nav-text ml-2 w-4 h-4 text-zinc-200 dark:text-zinc-200 transition-transform transform shrink-0 opacity-0 hidden" />
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="user-menu"
                        class="absolute right-0 bottom-full mb-2 w-48 py-2 bg-white dark:bg-zinc-700 rounded-lg shadow-xl border border-zinc-200 dark:border-zinc-700 z-50 hidden">
                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                            {{ __('Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-zinc-50 dark:hover:bg-zinc-600">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endauth
