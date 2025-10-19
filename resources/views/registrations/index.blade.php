@extends('layouts.front')

@section('title', 'Mes Inscriptions')

@section('content')
<div class="bg-gray-100 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Mes Inscriptions</h1>
        
        @if($registrations->isEmpty())
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg">
                <p>Vous n'êtes inscrit à aucun événement. <a href="{{ route('events.index') }}" class="font-bold hover:underline">Découvrez nos événements !</a></p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($registrations as $registration)
                    <div class="bg-white p-4 rounded-lg shadow-md flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold">
                                <a href="{{ route('events.show', $registration->event) }}" class="text-gray-800 hover:text-green-600">
                                    {{ $registration->event->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mt-1">
                                Le {{ $registration->event->start_date->format('d/m/Y') }} à {{ $registration->event->city }}
                            </p>
                        </div>
                        <form action="{{ route('registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Sûr ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 font-semibold rounded-lg hover:bg-red-200">Annuler</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection