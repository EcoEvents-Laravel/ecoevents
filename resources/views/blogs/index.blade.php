@extends(!auth()->user() ? 'layouts.app' : 'layouts.front')
@section(!auth()->user() ? 'title' : 'title', 'Blog - EcoEvents Knowledge Hub')

@section('content')
@if(!auth()->user())
<div class="container py-5">
    {{-- Hero Section --}}
    <section class="bg-success text-white py-5 mb-4 rounded">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">
                EcoEvents <span class="text-light">Knowledge Hub</span>
            </h1>
            <p class="lead mb-4">
                Découvrez les dernières tendances en événementiel durable, technologies vertes et innovation sociale
            </p>
            {{-- Search Bar --}}
            <form action="{{ route('blogs.index') }}" method="GET" class="row justify-content-center mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="Rechercher un article, un tag, un auteur..." value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            @auth
            <a href="{{ route('blogs.create') }}" class="btn btn-light fw-bold">
                <i class="bi bi-plus-lg"></i> Créer un Article
            </a>
            @endauth
        </div>
    </section>

    <div class="row">
        {{-- Articles --}}
        <main class="col-lg-8">
            @forelse ($blogs as $blog)
                <div class="card mb-4 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <a href="{{ route('blogs.show', $blog) }}">
                                <img src="https://picsum.photos/seed/{{ $blog->id }}/800/600" class="img-fluid rounded-start" alt="{{ $blog->title }}">
                            </a>
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                {{-- Tags --}}
                                <div class="mb-2">
                                    @foreach($blog->tags->take(3) as $tag)
                                        <span class="badge bg-success me-1">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <h5 class="card-title">
                                    <a href="{{ route('blogs.show', $blog) }}" class="text-decoration-none text-dark">{{ $blog->title }}</a>
                                </h5>
                                <p class="card-text">{{ Str::limit(strip_tags($blog->content), 180) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary rounded-circle me-2" style="width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;">
                                            {{ substr($blog->author->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <small class="text-muted">{{ $blog->author->name }}</small><br>
                                            <small class="text-muted">
                                                {{ $blog->publication_date?->format('d M Y') ?? 'Brouillon' }} •
                                                {{ ceil(strlen(strip_tags($blog->content)) / 1000) }} min
                                            </small>
                                        </div>
                                    </div>
                                    @auth
                                    <div>
                                        <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet article ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center p-5">
                    <h3 class="card-title mb-3">Aucun article pour le moment</h3>
                    <p class="card-text mb-3">Soyez le premier à partager vos idées !</p>
                    @auth
                    <a href="{{ route('blogs.create') }}" class="btn btn-success">Créer le premier article</a>
                    @endauth
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($blogs->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $blogs->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </main>

        {{-- Sidebar --}}
        <aside class="col-lg-4">
            <div class="card mb-4 sticky-top" style="top:2rem;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-tags"></i> Tags Populaires</h5>
                    @php
                        $popularTags = ['Durabilité', 'Innovation', 'Tech', 'Communauté', 'Éco-design', 'Événements'];
                    @endphp
                    <div>
                        @foreach($popularTags as $tag)
                            <a href="#" class="badge bg-secondary me-1 mb-1">#{{ $tag }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card mb-4 bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">📬 Newsletter EcoEvents</h5>
                    <p class="card-text">Recevez nos meilleurs articles chaque semaine</p>
                    <form>
                        <input type="email" class="form-control mb-2" placeholder="votre@email.com">
                        <button type="submit" class="btn btn-light w-100 text-success fw-bold">S'abonner</button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
@else
<div class="container py-5">
    {{-- Hero Section --}}
    <section class="bg-success text-white py-5 mb-4 rounded">
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">
                EcoEvents <span class="text-light">Knowledge Hub</span>
            </h1>
            <p class="lead mb-4">
                Découvrez les dernières tendances en événementiel durable, technologies vertes et innovation sociale
            </p>
            {{-- Search Bar --}}
            <form action="{{ route('blogs.index') }}" method="GET" class="row justify-content-center mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="Rechercher un article, un tag, un auteur..." value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            @auth
            <a href="{{ route('blogs.create') }}" class="btn btn-light fw-bold">
                <i class="bi bi-plus-lg"></i> Créer un Article
            </a>
            @endauth
        </div>
    </section>
    <div class="row">
        {{-- Articles --}}
        <main class="col-lg-8">
            @forelse ($blogs as $blog)
                <div class="card mb-4 shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <a href="{{ route('blogs.show', $blog) }}">
                                <img src="https://picsum.photos/seed/{{ $blog->id }}/800/600" class="img-fluid rounded-start" alt="{{ $blog->title }}">
                            </a>
                        </div>
                        <div class="col-md-7">
                            <div class="card-body">
                                {{-- Tags --}}
                                <div class="mb-2">
                                    @foreach($blog->tags->take(3) as $tag)
                                        <span class="badge bg-success me-1">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <h5 class="card-title">
                                    <a href="{{ route('blogs.show', $blog) }}" class="text-decoration-none text-dark">{{ $blog->title }}</a>
                                </h5>
                                <p class="card-text">{{ Str::limit(strip_tags($blog->content), 180) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary rounded-circle me-2" style="width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;">
                                            {{ substr($blog->author->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <small class="text-muted">{{ $blog->author->name }}</small><br>
                                            <small class="text-muted">
                                                {{ $blog->publication_date?->format('d M Y') ?? 'Brouillon' }} •
                                                {{ ceil(strlen(strip_tags($blog->content)) / 1000) }} min
                                            </small>
                                        </div>
                                    </div>
                                    @auth
                                    <div>
                                        <a href="{{ route('blogs.edit', $blog) }}" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet article ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endauth
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card text-center p-5">
                    <h3 class="card-title mb-3">Aucun article pour le moment</h3>
                    <p class="card-text mb-3">Soyez le premier à partager vos idées !</p>
                    @auth
                    <a href="{{ route('blogs.create') }}" class="btn btn-success">Créer le    premier article</a>
                    @endauth
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($blogs->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $blogs->links('pagination::bootstrap-4') }}
            </div>
            @endif
        </main>

        {{-- Sidebar --}}
        <aside class="col-lg-4">
            <div class="card mb-4 sticky-top" style="top:2rem;">    
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-tags"></i> Tags Populaires</h5>
                    @php
                        $popularTags = ['Durabilité', 'Innovation', 'Tech', 'Communauté', 'Éco-design', 'Événements'];
                    @endphp
                    <div>
                        @foreach($popularTags as $tag)
                            <a href="#" class="badge bg-secondary me-1 mb-1">#{{ $tag }}</a>
                        @endforeach
                    </div>  
                </div>
            </div>
            <div class="card mb-4 bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">📬 Newsletter EcoEvents</h5>
                    <p class="card-text">Recevez nos meilleurs articles chaque semaine</p>
                    <form action="#" method="POST" class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Votre email" required>
                        <button type="submit" class="btn btn-light">S'abonner</button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
@endif
