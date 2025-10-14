{{-- resources/views/blogs/show.blade.php --}}
@extends('layouts.app')

@section('title', $blog->title . ' - EcoEvents Blog')

@push('styles')
<style>
    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(to right, #10b981, #3b82f6);
        z-index: 9999;
        transition: width 0.1s ease;
    }
    .parallax-container {
        height: 350px;
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
    .comment-card {
        background: #f8f9fa;
        border-left: 4px solid #10b981;
    }
</style>
@endpush

@section('content')
<div class="reading-progress" id="readingProgress"></div>

<article>
    {{-- Hero Image avec effet Parallax --}}
    <div class="parallax-container mb-4" id="heroParallax">
        <img 
            src="https://picsum.photos/seed/{{ $blog->id }}/1920/1080" 
            alt="{{ $blog->title }}" 
            class="parallax-image"
            id="parallaxImage"
        >
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0.3), transparent);"></div>
        <div class="position-absolute bottom-0 start-0 end-0 p-4">
            <div class="container">
                <div class="mb-2">
                    @foreach($blog->tags as $tag)
                        <span class="badge bg-success me-1">{{ $tag->name }}</span>
                    @endforeach
                </div>
                <h1 class="display-4 fw-bold text-white mb-3">{{ $blog->title }}</h1>
                <div class="d-flex align-items-center text-white">
                    <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center fw-bold me-3" style="width:48px;height:48px;">
                        {{ substr($blog->author->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="fw-semibold mb-0">{{ $blog->author->name }}</p>
                        <small>
                            {{ $blog->publication_date?->format('d F Y') }}
                            <span class="mx-2">•</span>
                            {{ ceil(strlen(strip_tags($blog->content)) / 1000) }} min de lecture
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            {{-- Sidebar gauche partage --}}
            <div class="col-lg-1 d-none d-lg-block">
                <div class="sticky-top" style="top:100px;">
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-primary rounded-circle mb-2 share-btn" title="Partager sur Twitter">
                            <i class="bi bi-twitter"></i>
                        </button>
                        <button class="btn btn-primary rounded-circle mb-2 share-btn" title="Partager sur Facebook">
                            <i class="bi bi-facebook"></i>
                        </button>
                        <button class="btn btn-success rounded-circle mb-2 share-btn" title="Partager sur WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </button>
                        <button class="btn btn-dark rounded-circle mb-2 share-btn" title="Copier le lien">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Article principal --}}
            <main class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-body">

                        <div class="mb-4 article-content">
                            {!! nl2br(e($blog->content)) !!}
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <h5 class="text-muted mb-2">Tags</h5>
                            @foreach($blog->tags as $tag)
                                <a href="#" class="badge bg-success me-2">{{ $tag->name }}</a>
                            @endforeach
                        </div>

                        {{-- Partage social mobile --}}
                        <div class="mt-4 pt-3 border-top d-lg-none">
                            <h5 class="text-muted mb-2">Partager</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary rounded-circle share-btn"><i class="bi bi-twitter"></i></button>
                                <button class="btn btn-primary rounded-circle share-btn"><i class="bi bi-facebook"></i></button>
                                <button class="btn btn-success rounded-circle share-btn"><i class="bi bi-whatsapp"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Commentaires --}}
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h2 class="h4 fw-bold mb-4">💬 Commentaires</h2>
                        <div class="mb-4">
                            <textarea rows="4" placeholder="Partagez votre avis sur cet article..." class="form-control"></textarea>
                            <div class="d-flex justify-content-end mt-2">
                                <button class="btn btn-success">Publier le commentaire</button>
                            </div>
                        </div>
                        <div class="mb-3 comment-card p-3 rounded">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-gradient p-2 text-white fw-bold flex-shrink-0" style="background: linear-gradient(135deg,#a78bfa,#f472b6);width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                    M
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-semibold">Marie Dubois</span>
                                        <span class="text-muted small">il y a 2 heures</span>
                                    </div>
                                    <p class="mb-1">Excellent article ! Ces conseils vont vraiment m'aider pour organiser mon prochain événement éco-responsable. Merci pour le partage ! 🌱</p>
                                    <button class="btn btn-link btn-sm text-success p-0">Répondre</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 comment-card p-3 rounded">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle bg-gradient p-2 text-white fw-bold flex-shrink-0" style="background: linear-gradient(135deg,#60a5fa,#22d3ee);width:48px;height:48px;display:flex;align-items:center;justify-content:center;">
                                    J
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-semibold">Jean Martin</span>
                                        <span class="text-muted small">il y a 1 jour</span>
                                    </div>
                                    <p class="mb-1">Très intéressant ! Auriez-vous des recommandations spécifiques pour des événements de grande envergure ?</p>
                                    <button class="btn btn-link btn-sm text-success p-0">Répondre</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Sidebar droite --}}
            <aside class="col-lg-3">
                <div class="sticky-top" style="top:32px;">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="fw-bold mb-3">À propos de l'auteur</h5>
                            <div class="rounded-circle mx-auto mb-2 text-white fw-bold d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg,#34d399,#3b82f6);width:80px;height:80px;font-size:2rem;">
                                {{ substr($blog->author->name, 0, 1) }}
                            </div>
                            <h6 class="fw-semibold mb-1">{{ $blog->author->name }}</h6>
                            <p class="text-muted small mb-2">Passionné par l'événementiel durable et l'innovation sociale</p>
                            <button class="btn btn-success w-100">Suivre</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Articles similaires</h5>
                            @for($i = 1; $i <= 3; $i++)
                            <a href="#" class="d-flex gap-2 mb-3 text-decoration-none">
                                <img src="https://picsum.photos/seed/related{{ $i }}/100/100" alt="Article" class="rounded flex-shrink-0" style="width:48px;height:48px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold text-dark">Comment réduire l'empreinte carbone de vos événements</div>
                                    <small class="text-muted">3 min de lecture</small>
                                </div>
                            </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="bg-success bg-gradient py-5 mt-5">
        <div class="container text-center">
            <h2 class="h3 fw-bold text-white mb-2">Vous avez aimé cet article ?</h2>
            <p class="lead text-white-50 mb-3">Découvrez plus de contenu sur l'événementiel durable</p>
            <a href="{{ route('blogs.index') }}" class="btn btn-light fw-bold px-4 py-2 rounded-pill">Voir tous les articles</a>
        </div>
    </div>
</article>

@push('scripts')
<script>
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('readingProgress').style.width = scrolled + '%';
    });

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
