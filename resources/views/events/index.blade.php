@extends('layouts.front')

@section('title', 'Accueil')

@section('content')
    <header class="bg-gradient-to-br from-green-50 via-blue-50 to-green-100 py-20 text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-5xl font-black text-gray-900 mb-4">Rejoignez le Mouvement Vert</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Découvrez, participez et organisez des événements qui construisent un avenir durable.
            </p>
        </div>
    </header>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Événements à venir</h2>
        
        @if($events->isEmpty())
            <p>Aucun événement pour le moment.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($events as $event)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300">
                        <a href="{{ route('events.show', $event->id) }}">
                            <div class="p-6">
                                <span class="text-sm text-green-600 font-semibold">{{ $event->eventType->name ?? 'Catégorie' }}</span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3 h-14">{{ $event->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    📅 {{ $event->start_date->format('d/m/Y') }} | 📍 {{ $event->city }}
                                </p>
                                <span class="w-full text-center block px-4 py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 transition-colors duration-300">
                                    Voir les détails
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection