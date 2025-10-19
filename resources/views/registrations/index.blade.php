@extends('layouts.front')

@section('title', 'Mes Inscriptions')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mes Inscriptions</h1>
            <p class="text-gray-600 mb-6">Retrouvez ici la liste des événements auxquels vous êtes inscrit.</p>

            @if($registrations->isEmpty())
                <div class="bg-white rounded-xl shadow-md p-6 text-center">
                    <p class="text-gray-700 mb-4">Vous n'êtes inscrit à aucun événement pour le moment.</p>
                    <a href="{{ route('events.index') }}" class="inline-block px-5 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-all duration-200">
                        Voir les événements
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($registrations as $registration)
                        <div class="bg-white rounded-xl shadow-md p-4 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('events.show', $registration->event) }}" class="text-lg font-semibold text-gray-900 hover:text-green-600 transition-colors duration-200">
                                    {{ $registration->event->title }}
                                </a>
                                <p class="text-sm text-gray-500 mt-1">
                                    Le {{ \Carbon\Carbon::parse($registration->event->start_date)->format('d/m/Y') }}
                                    • {{ $registration->event->city }}
                                </p>
                            </div>

                            <form action="{{ route('registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler votre inscription ?');" class="flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 rounded-md hover:bg-red-100 transition-colors duration-150">
                                    Annuler
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-center">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection