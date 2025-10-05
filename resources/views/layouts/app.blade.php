<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoEvents - @yield('title', 'Bienvenue')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 70px; /* Ajoute de l'espace pour la navbar fixe */ }
        .error { color: red; list-style: none; padding-left: 0; }
    </style>
</head>
<body>

    <!-- ======== Barre de Navigation ======== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <strong>EcoEvents</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Liens à gauche -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('events.index') }}">Événements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('event-types.index') }}">Types d'Événements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tags.index') }}">Tags</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('badge.index') }}">Badges</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user_badge.index') }}">Badges des Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('organisations.index') }}">Organisations</a>
                    </li>
                </ul>

                <!-- Liens à droite -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <!-- Liens pour les visiteurs non connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Se connecter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">S'inscrire</a>
                        </li>
                    @else
                        <!-- Liens pour les utilisateurs connectés -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('registrations.index') }}">Mes Inscriptions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chatbot') }}">Chatbot AI</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link" href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Se déconnecter
                                </a>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- ======== Contenu de la Page ======== -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Oups ! Il y a eu des erreurs :</strong>
                <ul class="error mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>