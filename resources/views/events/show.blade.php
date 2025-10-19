@extends('layouts.front')

@section('title', $event->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            @if($event->banner_url)
                <img src="{{ asset('storage/' . $event->banner_url) }}" alt="Bannière" class="rounded-2xl shadow-lg mb-8 w-full h-96 object-cover">
            @endif
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $event->title }}</h1>
            <p class="text-gray-600 leading-relaxed mb-8">{!! nl2br(e($event->description)) !!}</p>

            <!-- ============================================= -->
            <!-- SECTION COMMENTAIRES (TA FONCTIONNALITÉ) -->
            <!-- ============================================= -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-6">Commentaires</h3>
                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-md mb-8">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <textarea name="content" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="Laissez votre commentaire..." required></textarea>
                        @error('content') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <button type="submit" class="mt-4 px-6 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-all duration-300">Publier</button>
                    </form>
                @else
                    <p class="mb-8 bg-gray-100 p-4 rounded-lg"><a href="{{ route('login') }}" class="font-semibold text-green-600 hover:underline">Connectez-vous</a> pour laisser un commentaire.</p>
                @endauth
                <div class="space-y-6">
                    @forelse ($event->comments->sortByDesc('created_at') as $comment)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</div>
                            <div class="flex-grow bg-white p-4 rounded-xl shadow">
                                <div class="flex justify-between items-center">
                                    <p class="font-bold">{{ $comment->user->name }}</p>
                                    <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $comment->content }}</p>
                                @can('delete', $comment)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Sûr ?');" class="text-right mt-2">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucun commentaire pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-white p-6 rounded-2xl shadow-lg">
                <h3 class="text-xl font-bold mb-4">Informations</h3>
                <ul class="space-y-3 text-gray-700">
                    <li><strong>Type :</strong> {{ $event->eventType->name ?? 'N/A' }}</li>
                    <li><strong>Début :</strong> {{ $event->start_date->format('d/m/Y H:i') }}</li>
                    <li><strong>Fin :</strong> {{ $event->end_date->format('d/m/Y H:i') }}</li>
                    <li><strong>Lieu :</strong> {{ $event->city }}</li>
                </ul>
                <hr class="my-6">
                <!-- ============================================= -->
                <!-- SECTION INSCRIPTION (TA FONCTIONNALITÉ) -->
                <!-- ============================================= -->
                @auth
                    @if($userRegistration)
                        <form action="{{ route('registrations.destroy', $userRegistration) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition-all duration-300">Annuler l'inscription</button>
                        </form>
                    @else
                        <form action="{{ route('registrations.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="w-full px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-all duration-300">S'inscrire</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full block text-center px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-all duration-300">Connectez-vous pour vous inscrire</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection