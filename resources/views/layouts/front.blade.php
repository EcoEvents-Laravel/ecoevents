{{-- resources/views/layouts/front.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="EcoEvents - Plateforme d'événements durables et éco-responsables">
    <title>@yield('title', 'EcoEvents') - {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🌱</text></svg>">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Styles supplémentaires --}}
    @stack('styles')

    <style>
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Navigation sticky avec effet blur */
        .navbar-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Animation du menu mobile */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .mobile-menu.active {
            transform: translateX(0);
        }

        /* Notification toast */
        .toast {
            animation: slideInDown 0.5s ease, slideOutUp 0.5s ease 4.5s;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOutUp {
            from {
                transform: translateY(0);
                opacity: 1;
            }
            to {
                transform: translateY(-100%);
                opacity: 0;
            }
        }

        /* Footer wave effect */
        .wave {
            position: absolute;
            top: -100px;
            left: 0;
            width: 100%;
            height: 100px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120'%3E%3Cpath d='M321.39 56.44c58-10.79 114.16-30.13 172-41.86 82.39-16.72 168.19-17.73 250.45-.39C823.78 31 906.67 72 985.66 92.83c70.05 18.48 146.53 26.09 214.34 3V0H0v27.35a600.21 600.21 0 00321.39 29.09z' fill='%23ffffff'/%3E%3C/svg%3E");
            background-size: cover;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div id="app" class="min-h-screen flex flex-col">
        
        {{-- Navigation principale --}}
        <nav class="navbar-blur sticky top-0 z-50 shadow-md transition-all duration-300" id="navbar">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    
                    {{-- Logo --}}
                    <div class="flex-shrink-0">
                        <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                            <div class="h-10 w-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                <span class="text-2xl">🌱</span>
                            </div>
                            <span class="text-2xl font-black text-gray-900">
                                Eco<span class="text-green-600">Events</span>
                            </span>
                        </a>
                    </div>

                    {{-- Navigation Desktop --}}
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group">
                            Accueil
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group">
                            Événements
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('blogs.index') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group {{ request()->routeIs('blogs.*') ? 'text-green-600' : '' }}">
                            Blog
                            <span class="absolute bottom-0 left-0 {{ request()->routeIs('blogs.*') ? 'w-full' : 'w-0' }} h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{route('badge.index')}}" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group">
                            Badges
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="{{route('chatbot')}}" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group">
                            Chatbot
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                        <a href="#" class="text-gray-700 hover:text-green-600 font-medium transition-colors duration-300 relative group">
                            À propos
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-green-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </div>

                    {{-- Actions utilisateur --}}
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600">Bonjour, <strong>{{ auth()->user()->name }}</strong></span>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-gray-700 hover:text-red-600 font-medium transition-colors duration-300">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-colors duration-300">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white font-semibold rounded-lg hover:from-green-600 hover:to-blue-600 transition-all duration-300 shadow-md hover:shadow-lg">
                                S'inscrire
                            </a>
                        @endauth
                    </div>

                    {{-- Bouton Menu Mobile --}}
                    <button class="md:hidden text-gray-700 hover:text-green-600 focus:outline-none" id="mobileMenuBtn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Menu Mobile --}}
        <div class="mobile-menu fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50" id="mobileMenu">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <span class="text-2xl font-black text-gray-900">Menu</span>
                    <button class="text-gray-700 hover:text-red-600" id="closeMobileMenu">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-4">
                    <a href="{{ route('welcome') }}" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg font-medium transition-all duration-300">
                        🏠 Accueil
                    </a>
                    <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg font-medium transition-all duration-300">
                        🎉 Événements
                    </a>
                    <a href="{{ route('blogs.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg font-medium transition-all duration-300">
                        📝 Blog
                    </a>
                    <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-lg font-medium transition-all duration-300">
                        ℹ️ À propos
                    </a>
                    
                    @auth
                        <hr class="my-6">
                        <a href="{{ route('blogs.create') }}" class="block px-4 py-3 bg-green-500 text-white text-center rounded-lg font-semibold hover:bg-green-600 transition-all duration-300">
                            ✨ Créer un article
                        </a>
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-all duration-300">
                                🚪 Déconnexion
                            </button>
                        </form>
                    @else
                        <hr class="my-6">
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-center border-2 border-green-500 text-green-600 rounded-lg font-semibold hover:bg-green-50 transition-all duration-300">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-3 text-center bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-lg font-semibold hover:from-green-600 hover:to-blue-600 transition-all duration-300">
                            S'inscrire
                        </a>
                    @endauth
                </nav>
            </div>
        </div>

        {{-- Overlay pour le menu mobile --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="mobileMenuOverlay"></div>

        {{-- Notifications Toast --}}
        @if(session('success'))
            <div class="toast fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 max-w-md">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="toast fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 max-w-md">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Contenu principal --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white mt-auto pt-20">
            
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    
                    {{-- À propos --}}
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
                            <span class="text-3xl">🌱</span>
                            EcoEvents
                        </h3>
                        <p class="text-gray-400 leading-relaxed">
                            Votre plateforme pour organiser et découvrir des événements éco-responsables et durables.
                        </p>
                        <div class="flex gap-3">
                            <a href="#" class="h-10 w-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="h-10 w-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="h-10 w-10 bg-white/10 hover:bg-white/20 rounded-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Liens rapides --}}
                    <div>
                        <h4 class="text-lg font-bold mb-4">Navigation</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('welcome') }}" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Accueil</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Événements</a></li>
                            <li><a href="{{ route('blogs.index') }}" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Blog</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">À propos</a></li>
                        </ul>
                    </div>

                    {{-- Ressources --}}
                    <div>
                        <h4 class="text-lg font-bold mb-4">Ressources</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Guide de l'organisateur</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">FAQ</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Support</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Communauté</a></li>
                        </ul>
                    </div>

                    {{-- Newsletter --}}
                    <div>
                        <h4 class="text-lg font-bold mb-4">Newsletter</h4>
                        <p class="text-gray-400 mb-4 text-sm">Recevez nos dernières actualités</p>
                        <form class="space-y-3">
                            <input type="email" placeholder="votre@email.com" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-green-500 transition-colors duration-300">
                            <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all duration-300">
                                S'abonner
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Copyright --}}
                <div class="mt-12 pt-8 border-t border-white/10 text-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} EcoEvents. Tous droits réservés. 
                        <span class="mx-2">|</span>
                        <a href="#" class="hover:text-green-400 transition-colors duration-300">Politique de confidentialité</a>
                        <span class="mx-2">|</span>
                        <a href="#" class="hover:text-green-400 transition-colors duration-300">Conditions d'utilisation</a>
                    </p>
                </div>
            </div>
        </footer>

    </div>

    {{-- Scripts supplémentaires --}}
    @stack('scripts')

    <script>
        // Menu mobile
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

        function openMobileMenu() {
            mobileMenu.classList.add('active');
            mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenuFunc() {
            mobileMenu.classList.remove('active');
            mobileMenuOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        mobileMenuBtn?.addEventListener('click', openMobileMenu);
        closeMobileMenu?.addEventListener('click', closeMobileMenuFunc);
        mobileMenuOverlay?.addEventListener('click', closeMobileMenuFunc);

        // Navbar au scroll
        let lastScrollTop = 0;
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }

            lastScrollTop = scrollTop;
        });

        // Auto-dismiss des toasts après 5 secondes
        setTimeout(() => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => toast.remove());
        }, 5000);
    </script>

</body>
</html>