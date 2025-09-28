@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="row">
    <!-- Colonne de gauche : Informations -->
    <div class="col-md-8">
        @if($event->banner_url)
            <img src="{{ asset('storage/' . $event->banner_url) }}" alt="Bannière de l'événement" class="img-fluid rounded shadow-sm mb-4">
        @endif
        
        <h1 class="display-4">{{ $event->title }}</h1>
        <p class="text-muted mb-4">Organisé par : {{ $event->user->name ?? 'Organisateur inconnu' }}</p>

        <div class="mb-4">
            {!! nl2br(e($event->description)) !!}
        </div>

        <!-- SECTION COMMENTAIRES -->
        <hr class="my-4">
        <h3 class="mb-3">Commentaires</h3>

        @auth
            <form action="{{ route('comments.store') }}" method="POST" class="mb-4 card card-body">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="mb-3">
                    <label for="content" class="form-label">Votre commentaire</label>
                    <textarea name="content" id="content" rows="4" class="form-control @error('content') is-invalid @enderror" required minlength="10">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
            </form>
        @else
            <p class="mb-4"><a href="{{ route('login') }}">Connectez-vous</a> pour laisser un commentaire.</p>
        @endauth

        <div class="list-group">
            @forelse ($event->comments->sortByDesc('created_at') as $comment)
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $comment->user->name }}</h5>
                        <small>{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ nl2br(e($comment->content)) }}</p>
                    @can('delete', $comment)
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');" class="text-end">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    @endcan
                </div>
            @empty
                <p>Aucun commentaire pour le moment.</p>
            @endforelse
        </div>
    </div>

    <!-- Colonne de droite : Méta-données et Inscription -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Informations
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Type :</strong> {{ $event->eventType->name ?? 'N/A' }}</li>
                <li class="list-group-item"><strong>Tags :</strong> {{ $event->tags->pluck('name')->implode(', ') }}</li>
                <li class="list-group-item"><strong>Début :</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Fin :</strong> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Lieu :</strong> {{ $event->address }}, {{ $event->city }}</li>
            </ul>
            <div class="card-body">
                <!-- SECTION INSCRIPTION -->
                @auth
                    @if($userRegistration)
                        <form action="{{ route('registrations.destroy', $userRegistration) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Annuler mon inscription</button>
                        </form>
                        <p class="text-success text-center mt-2 small">Vous êtes inscrit.</p>
                    @else
                        <form action="{{ route('registrations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="btn btn-success w-100">S'inscrire à l'événement</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">Connectez-vous pour vous inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection