<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EcoEvents - Plateforme d'événements durables et éco-responsables">
    <title>@yield('title', 'EcoEvents') - {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🌱</text></svg>">

    {{-- ========================================================== --}}
    {{-- C'EST LA LIGNE LA PLUS IMPORTANTE : ELLE APPELLE VITE ! --}}
    {{-- ========================================================== --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div id="app" class="min-h-screen flex flex-col">
        
        {{-- Navigation principale --}}
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    
                    {{-- Logo --}}
                    <a href="{{ route('welcome') }}" class="flex items-center gap-2">
                        <span class="text-2xl">🌱</span>
                        <span class="text-xl font-bold text-gray-900">EcoEvents</span>
                    </a>

                    {{-- Navigation Desktop --}}
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-green-600 font-medium">Accueil</a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-green-600 font-medium">Événements</a>
                    </div>

                    {{-- Actions utilisateur --}}
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a href="{{ route('registrations.index') }}" class="text-gray-700 hover:text-green-600 font-medium">Mes Inscriptions</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Déconnexion</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 font-medium">Connexion</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600">S'inscrire</a>
                        @endauth
                    </div>

                    {{-- Bouton Menu Mobile (à implémenter en JS si besoin) --}}
                    <button class="md:hidden text-gray-700">Menu</button>
                </div>
            </div>
        </nav>

        {{-- Notifications Toast (exemple) --}}
        @if(session('success'))
            <div class="bg-green-500 text-white text-center py-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- Contenu principal --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-gray-800 text-white mt-auto py-8">
             <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} EcoEvents. Tous droits réservés.</p>
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>