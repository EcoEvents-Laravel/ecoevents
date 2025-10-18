@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.front')
@section('title', auth()->user()->role === 'admin' ? 'Gestion des Articles' : 'Knowledge Hub - Articles EcoEvents')

@section('content')
@if(auth()->user()->role === 'admin')
<div class="container py-5">
    <h2 class="mb-4">Gestion des Articles</h2>
    <a href="{{ route('blogs.create') }}" class="btn btn-primary mb-3">Créer un Nouvel Article</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Author</th>
                <th>Description</th>
                <th>Tags</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->blogType->name ?? 'N/A' }}</td>
                    <td>{{ optional($blog->author)->name ?? 'N/A' }}</td>
                    <td>{{ $blog->content }}</td>
                    <td>{{ optional($blog->tags)->count() ? $blog->tags->pluck('name')->implode(', ') : 'No tags' }}</td>
                    <td>{{ \Carbon\Carbon::parse($blog->publication_date)->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('blogs.destroy', $blog) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $blogs->links() }}
</div>
@else
@push('styles')
<style>
    /* Variables CSS pour la cohérence */
    :root {
        --eco-primary: #10b981;
        --eco-secondary: #3b82f6;
        --eco-accent: #8b5cf6;
        --eco-dark: #1f2937;
        --eco-light: #f9fafb;
    }

    /* Hero Section avec Gradient Animé */
    .hero-gradient {
        background: linear-gradient(-45deg, #10b981, #3b82f6, #8b5cf6, #ec4899);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Card avec effet Glassmorphism */
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .glass-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        border-color: var(--eco-primary);
    }

    /* Animation d'apparition au scroll */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Tag animé */
    .tag-pill {
        transition: all 0.3s ease;
    }

    .tag-pill:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Image overlay */
    .blog-image-container {
        position: relative;
        overflow: hidden;
    }

    .blog-image-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .glass-card:hover .blog-image-container::after {
        opacity: 1;
    }

    /* Bouton d'action flottant */
    .action-btn {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.3s ease;
    }

    .glass-card:hover .action-btn {
        opacity: 1;
        transform: scale(1);
    }

    /* Search bar moderne */
    .search-container {
        position: relative;
    }

    .search-container input:focus {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
</style>
@endpush
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-green-50">
    
    {{-- Hero Section Dynamique --}}
    <section class="hero-gradient text-white py-20 px-4 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="container mx-auto relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-black mb-6 tracking-tight">
                EcoEvents <span class="text-green-300">Knowledge Hub</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-100 max-w-3xl mx-auto font-light">
                Découvrez les dernières tendances en événementiel durable, technologies vertes et innovation sociale
            </p>
            
            {{-- Search Bar Moderne --}}
            <div class="max-w-2xl mx-auto search-container">
                <form action="{{ route('blogs.index') }}" method="GET" class="relative">
                    <input 
                        type="search" 
                        name="search" 
                        placeholder="Rechercher un article, un tag, un auteur..." 
                        value="{{ request('search') }}"
                        class="w-full px-6 py-4 pr-14 rounded-full text-gray-800 text-lg focus:outline-none transition-all duration-300"
                    >
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-green-500 hover:bg-green-600 text-white p-3 rounded-full transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Articles Column --}}
            <main class="lg:col-span-2">
                <div class="space-y-8">
                    @forelse ($blogs as $blog)
                        <article class="glass-card rounded-3xl overflow-hidden fade-in-up" data-delay="{{ $loop->index * 100 }}">
                            <div class="grid md:grid-cols-5 gap-0">
                                
                                {{-- Image Section --}}
                                <div class="md:col-span-2 blog-image-container">
                                    <a href="{{ route('blogs.show', $blog) }}">
                                        <img 
                                            src="https://picsum.photos/seed/{{ $blog->id }}/800/600" 
                                            alt="{{ $blog->title }}" 
                                            class="w-full h-64 md:h-full object-cover transition-transform duration-700 hover:scale-110"
                                            loading="lazy"
                                        >
                                    </a>
                                </div>

                                {{-- Content Section --}}
                                <div class="md:col-span-3 p-6 md:p-8 relative">
                                    
                                    {{-- Tags --}}
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach($blog->tags->take(3) as $tag)
                                            <span class="tag-pill inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>

                                    {{-- Title --}}
                                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 leading-tight hover:text-green-600 transition-colors duration-300">
                                        <a href="{{ route('blogs.show', $blog) }}">
                                            {{ $blog->title }}
                                        </a>
                                    </h2>

                                    {{-- Excerpt --}}
                                    <p class="text-gray-600 text-base leading-relaxed mb-6">
                                        {{ Str::limit(strip_tags($blog->content), 180) }}
                                    </p>

                                    {{-- Meta Info --}}
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($blog->author->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 text-sm">{{ $blog->author->name }}</p>
                                                <p class="text-gray-500 text-xs">
                                                    {{ $blog->publication_date?->format('d M Y') ?? 'Brouillon' }}
                                                    <span class="mx-1">•</span>
                                                    {{ ceil(strlen(strip_tags($blog->content)) / 1000) }} min
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="glass-card rounded-3xl p-16 text-center">
                            <div class="max-w-md mx-auto">
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <h3 class="text-3xl font-bold text-gray-900 mb-4">Aucun article pour le moment</h3>
                                <p class="text-gray-600 mb-6">Soyez le premier à partager vos idées !</p>
                                @auth
                                <a href="{{ route('blogs.create') }}" class="inline-flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-600 transition-all duration-300">
                                    Créer le premier article
                                </a>
                                @endauth
                            </div>
                        </div>
                    @endforelse

                    {{-- Pagination Élégante --}}
                    @if($blogs->hasPages())
                    <div class="flex justify-center mt-12">
                        {{ $blogs->links('pagination::tailwind') }}
                    </div>
                    @endif
                </div>
            </main>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                
                {{-- Tags Populaires --}}
                <div class="glass-card rounded-2xl p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        Tags Populaires
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $popularTags = ['Durabilité', 'Innovation', 'Tech', 'Communauté', 'Éco-design', 'Événements'];
                        @endphp
                        @foreach($popularTags as $tag)
                            <a href="#" class="tag-pill bg-gray-100 text-gray-700 hover:bg-green-100 hover:text-green-800 text-sm font-medium px-4 py-2 rounded-full">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Newsletter --}}
                <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-green-500 to-blue-600 text-white">
                    <h3 class="text-xl font-bold mb-3">📬 Newsletter EcoEvents</h3>
                    <p class="text-sm mb-4 text-green-50">Recevez nos meilleurs articles chaque semaine</p>
                    <form class="space-y-3">
                        <input type="email" placeholder="votre@email.com" class="w-full px-4 py-2 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                        <button type="submit" class="w-full bg-white text-green-600 font-semibold py-2 rounded-lg hover:bg-green-50 transition-colors duration-300">
                            S'abonner
                        </button>
                    </form>
                </div>
            </aside>

        </div>
    </div>
</div>
<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, entry.target.dataset.delay || 0);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });
    });
</script>
@endif
@endsection
