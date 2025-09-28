@extends('layouts.app')

@section('title', 'Mes Inscriptions')

@section('content')
    <div class="container">
        <h1 class="mb-4">Mes Inscriptions</h1>
        <p>Retrouvez ici la liste des événements auxquels vous êtes inscrit.</p>
        
        @if($registrations->isEmpty())
            <div class="alert alert-info">
                Vous n'êtes inscrit à aucun événement pour le moment. <a href="{{ route('events.index') }}">Voir les événements.</a>
            </div>
        @else
            <div class="list-group">
                @foreach ($registrations as $registration)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <a href="{{ route('events.show', $registration->event) }}">
                                    {{ $registration->event->title }}
                                </a>
                            </h5>
                            <p class="mb-1 text-muted">
                                Le {{ \Carbon\Carbon::parse($registration->event->start_date)->format('d/m/Y') }}
                                à {{ $registration->event->city }}
                            </p>
                        </div>
                        <form action="{{ route('registrations.destroy', $registration) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment annuler votre inscription ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
@endsection