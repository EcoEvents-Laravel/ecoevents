{{-- resources/views/blogs/show.blade.php --}}
@extends('layouts.front')

@section('title', $blog->title . ' - EcoEvents Blog')

@push('styles')
<style>
    /* Lecture optimisée */
    .article-content {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #374151;
    }

    .article-content h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        color: #111827;
    }

    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }

    .article-content a {
        color: #10b981;
        text-decoration: underline;
        transition: color 0.3s ease;
    }

    .article-content a:hover {
        color: #059669;
    }

    /* Image hero parallax */
    .parallax-container {
        height: 60vh;
        overflow: hidden;
        position: relative;
    }

    .parallax-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 120%;
        object-fit: cover;
        transform: translateY(0);
        transition: transform 0.1s ease-out;
    }

    /* Progress bar de lecture */
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(to right, #10b981, #3b82f6);
        z-index: 9999;
        transition: width 0.1s ease;
    }

    /* Boutons de partage flottants */
    .share-buttons {
        position: sticky;
        top: 100px;
    }

    .share-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .share-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    /* Commentaires style moderne */
    .comment-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-left: 4px solid #10b981;
    }
</style>
@endpush

@section('content')
<div class="reading-progress" id="readingProgress"></div>

<article class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    
    {{-- Hero Image avec effet Parallax --}}
    <div class="parallax-container" id="heroParallax">
        <img 
            src="https://picsum.photos/seed/{{ $blog->id }}/1920/1080" 
            alt="{{ $blog->title }}" 
            class="parallax-image"
            id="parallaxImage"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        
        {{-- Titre superposé --}}
        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16">
            <div class="container mx-auto max-w-4xl">
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($blog->tags as $tag)
                        <span class="bg-green-500 text-white text-sm font-semibold px-4 py-1 rounded-full">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">
                    {{ $blog->title }}
                </h1>
                <div class="flex items-center gap-4 text-white">
                    <div class="h-12 w-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center font-bold">
                        {{ substr($blog->author->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $blog->author->name }}</p>
                        <p class="text-sm text-gray-200">
                            {{ $blog->publication_date?->format('d F Y') }}
                            <span class="mx-2">•</span>
                            {{ ceil(strlen(strip_tags($blog->content)) / 1000) }} min de lecture
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contenu Principal --}}
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- Boutons de partage (sidebar gauche) --}}
            <aside class="lg:col-span-1 hidden lg:block">
                <div class="share-buttons space-y-4">
                    <button class="share-btn bg-blue-500 hover:bg-blue-600 text-white" title="Partager sur Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                        </svg>
                    </button>
                    <button class="share-btn bg-blue-600 hover:bg-blue-700 text-white" title="Partager sur Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </button>
                    <button class="share-btn bg-green-500 hover:bg-green-600 text-white" title="Partager sur WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </button>
                    <button class="share-btn bg-gray-700 hover:bg-gray-800 text-white" title="Copier le lien">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>
            </aside>

            {{-- Contenu de l'article --}}
            <main class="lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
                    
                    

                    {{-- Contenu formaté --}}
                    <div class="article-content prose prose-lg max-w-none">
                        {!! nl2br(e($blog->content)) !!}
                    </div>

                    {{-- Tags --}}
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($blog->tags as $tag)
                                <a href="#" class="inline-flex items-center gap-2 bg-green-100 text-green-800 hover:bg-green-200 px-4 py-2 rounded-full text-sm font-medium transition-colors duration-300">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Partage social (mobile) --}}
                    <div class="mt-8 pt-8 border-t border-gray-200 lg:hidden">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Partager</h3>
                        <div class="flex gap-3">
                            <button class="share-btn bg-blue-500 hover:bg-blue-600 text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                                </svg>
                            </button>
                            <button class="share-btn bg-blue-600 hover:bg-blue-700 text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                                </svg>
                            </button>
                            <button class="share-btn bg-green-500 hover:bg-green-600 text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Section Commentaires (placeholder) --}}
                <div class="mt-12 bg-white rounded-3xl shadow-xl p-8 md:p-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">💬 Commentaires</h2>
                    
                    {{-- Formulaire de commentaire --}}
                    <div class="mb-12">
                        <textarea 
                            rows="4" 
                            placeholder="Partagez votre avis sur cet article..." 
                            class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:border-green-500 focus:outline-none transition-colors duration-300 resize-none"
                        ></textarea>
                        <div class="flex justify-end mt-4">
                            <button formaction="{{ route('comments.store') }}" formmethod="POST" class="px-8 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition-all duration-300 shadow-md hover:shadow-lg">
                                Publier le commentaire
                            </button>
                        </div>
                    </div>

                    {{-- Exemples de commentaires --}}
                    <div class="space-y-6">
                        <div class="comment-card p-6 rounded-2xl">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    M
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900">Marie Dubois</span>
                                        <span class="text-sm text-gray-500">il y a 2 heures</span>
                                    </div>
                                    <p class="text-gray-700">Excellent article ! Ces conseils vont vraiment m'aider pour organiser mon prochain événement éco-responsable. Merci pour le partage ! 🌱</p>
                                    <button class="mt-3 text-sm text-green-600 hover:text-green-700 font-medium">Répondre</button>
                                </div>
                            </div>
                        </div>

                        <div class="comment-card p-6 rounded-2xl">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    J
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900">Jean Martin</span>
                                        <span class="text-sm text-gray-500">il y a 1 jour</span>
                                    </div>
                                    <p class="text-gray-700">Très intéressant ! Auriez-vous des recommandations spécifiques pour des événements de grande envergure ?</p>
                                    <button class="mt-3 text-sm text-green-600 hover:text-green-700 font-medium">Répondre</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Sidebar droite - Articles similaires --}}
            <aside class="lg:col-span-3">
                <div class="sticky top-8 space-y-6">
                    {{-- À propos de l'auteur --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">À propos de l'auteur</h3>
                        <div class="text-center">
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                                {{ substr($blog->author->name, 0, 1) }}
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $blog->author->name }}</h4>
                            <p class="text-sm text-gray-600 mb-4">Passionné par l'événementiel durable et l'innovation sociale</p>
                            <button class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-300 font-medium">
                                Suivre
                            </button>
                        </div>
                    </div>

                    {{-- Articles similaires --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Articles similaires</h3>
                        <div class="space-y-4">
                            @for($i = 1; $i <= 3; $i++)
                            <a href="#" class="block group">
                                <div class="flex gap-3">
                                    <img src="https://picsum.photos/seed/related{{ $i }}/100/100" alt="Article" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                    <div>
                                        <h4 class="font-semibold text-sm text-gray-900 group-hover:text-green-600 transition-colors duration-300 line-clamp-2">
                                            Comment réduire l'empreinte carbone de vos événements
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">3 min de lecture</p>
                                    </div>
                                </div>
                            </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    {{-- Call to Action --}}
    <div class="bg-gradient-to-r from-green-500 to-blue-600 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Vous avez aimé cet article ?
            </h2>
            <p class="text-xl text-green-50 mb-8">
                Découvrez plus de contenu sur l'événementiel durable
            </p>
            <a href="{{ route('blogs.index') }}" class="inline-block px-8 py-4 bg-white text-green-600 font-bold rounded-full hover:bg-green-50 transform hover:scale-105 transition-all duration-300 shadow-xl">
                Voir tous les articles
            </a>
        </div>
    </div>
</article>

@push('scripts')
<script>
    // Progress bar de lecture
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('readingProgress').style.width = scrolled + '%';
    });

    // Effet parallax sur l'image hero
    const parallaxImage = document.getElementById('parallaxImage');
    const heroParallax = document.getElementById('heroParallax');
    
    if (parallaxImage && heroParallax) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroHeight = heroParallax.offsetHeight;
            
            if (scrolled < heroHeight) {
                const offset = scrolled * 0.5;
                parallaxImage.style.transform = `translateY(${offset}px)`;
            }
        });
    }

    // Copier le lien
    document.querySelectorAll('.share-btn').forEach(btn => {
        if (btn.title === 'Copier le lien') {
            btn.addEventListener('click', () => {
                navigator.clipboard.writeText(window.location.href);
                alert('Lien copié !');
            });
        }
    });
</script>
@endpush
@endsection