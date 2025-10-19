@extends('layouts.front')

@section('title', $event->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Colonne principale -->
        <div class="md:col-span-2 space-y-6">
            @if($event->banner_url)
                <img src="{{ asset('storage/' . $event->banner_url) }}" alt="Bannière de l'événement" class="w-full h-64 object-cover rounded-xl shadow-lg">
            @endif

            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-3xl font-extrabold mb-2">{{ $event->title }}</h1>
                <p class="text-sm text-gray-500 mb-4">Organisé par : <span class="text-gray-700 font-medium">{{ $event->user->name ?? 'Organisateur inconnu' }}</span></p>

                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            <!-- Commentaires -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-xl font-semibold mb-4">Commentaires</h3>

                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Votre commentaire</label>
                            <textarea name="content" id="content" rows="4" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-300 @error('content') ring-red-400 border-red-400 @enderror" required minlength="10">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow transition">Publier</button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-600 mb-4">
                        <a href="{{ route('login') }}" class="text-green-600 hover:underline">Connectez-vous</a> pour laisser un commentaire.
                    </p>
                @endauth

                <div class="space-y-4 mt-4">
                    @forelse ($event->comments->sortByDesc('created_at') as $comment)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h5 class="font-medium text-gray-800">{{ $comment->user->name }}</h5>
                                    <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                                @can('delete', $comment)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                    </form>
                                @endcan
                            </div>
                            <div class="mt-3 text-gray-700">
                                {!! nl2br(e($comment->content)) !!}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Aucun commentaire pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <aside class="space-y-6">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h4 class="font-semibold text-lg">Informations</h4>
                </div>
                <ul class="px-6 py-4 space-y-3 text-sm text-gray-700">
                    <li><span class="font-medium">Type :</span> {{ $event->eventType->name ?? 'N/A' }}</li>
                    <li><span class="font-medium">Tags :</span> {{ $event->tags->pluck('name')->implode(', ') }}</li>
                    <li><span class="font-medium">Début :</span> {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</li>
                    <li><span class="font-medium">Fin :</span> {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</li>
                    <li><span class="font-medium">Lieu :</span> {{ $event->address }}, {{ $event->city }}</li>
                </ul>

                <div class="px-6 py-4 border-t">
                    @auth
                        @if($userRegistration)
                            <form action="{{ route('registrations.destroy', $userRegistration) }}" method="POST" onsubmit="return confirm('Voulez-vous annuler votre inscription ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow">Annuler mon inscription</button>
                            </form>
                            <p class="text-sm text-green-600 text-center mt-3">Vous êtes inscrit.</p>
                        @else
                            <form action="{{ route('registrations.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg shadow">S'inscrire à l'événement</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Connectez-vous pour vous inscrire</a>
                    @endauth
                </div>
            </div>

            <!-- Cartes additionnelles (facultatif) -->
            <div class="bg-white rounded-xl shadow p-4">
                <h5 class="text-sm font-semibold text-gray-800 mb-2">Organisateur</h5>
                <p class="text-sm text-gray-600">{{ $event->user->name ?? 'Organisateur inconnu' }}</p>

                @if($event->user && $event->user->bio)
                    <p class="text-sm text-gray-500 mt-2">{{ Str::limit($event->user->bio, 120) }}</p>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection