@extends('layouts.app')

@section('title', 'Modifier l\'article - ' . $blog->title)

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('blogs.show', $blog) }}" class="btn btn-link">
            &larr; Retour à l'article
        </a>
        <h1 class="display-4">✏️ Modifier l'article</h1>
        <p class="text-muted">Apportez des modifications à votre article</p>
    </div>

    <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                {{-- Titre --}}
                <div class="mb-4">
                    <label for="title" class="form-label fw-bold">📝 Titre de l'article *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $blog->title) }}"
                        placeholder="Ex: Comment organiser un festival zéro déchet"
                        class="form-control @error('title') is-invalid @enderror"
                        required
                        maxlength="200"
                    >
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Un titre accrocheur attire plus de lecteurs</small>
                        <small class="text-muted" id="titleCount">0/200</small>
                    </div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contenu --}}
                <div class="mb-4">
                    <label for="content" class="form-label fw-bold">📄 Contenu de l'article *</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="10"
                        placeholder="Rédigez votre article ici... Utilisez des paragraphes clairs et concis."
                        class="form-control @error('content') is-invalid @enderror"
                        required
                    >{{ old('content', $blog->content) }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">💡 Conseil : Visez 800-1500 mots pour un article complet</small>
                        <small class="text-muted" id="wordCount">0 mots</small>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">🖼️ Image principale</label>
                    @if($blog->image_url)
                        <div class="mb-2">
                            <p class="text-muted mb-1">Image actuelle :</p>
                            <div class="position-relative d-inline-block">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-thumbnail" style="width: 200px; height: 130px; object-fit: cover;">
                                <button type="button" id="removeCurrentImage" class="btn btn-sm btn-danger position-absolute top-0 end-0">
                                    &times;
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="mb-2">
                        <input type="file" id="image" name="image" accept="image/*" class="form-control">
                        <small class="text-muted">PNG, JPG, WebP jusqu'à 10MB</small>
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">🏷️ Tags</label>
                    <input 
                        type="text" 
                        id="tagInput" 
                        placeholder="Ajoutez des tags (Entrée pour valider)"
                        class="form-control mb-2"
                    >
                    <div id="tagContainer" class="mb-2"></div>
                    <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $blog->tags->pluck('name')->implode(',')) }}">
                    <small class="text-muted">Appuyez sur Entrée pour ajouter chaque tag. Ex: Durabilité, Innovation, Tech</small>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Paramètres</h5>
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut de publication</label>
                            <select 
                                id="status" 
                                name="status" 
                                class="form-select"
                            >
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>✅ Publié</option>
                                <option value="archived" {{ old('status', $blog->status) == 'archived' ? 'selected' : '' }}>📦 Archivé</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="publication_date" class="form-label">Date de publication</label>
                            <input 
                                type="datetime-local" 
                                id="publication_date" 
                                name="publication_date" 
                                value="{{ old('publication_date', $blog->publication_date ? $blog->publication_date->format('Y-m-d\TH:i') : '') }}"
                                class="form-control"
                            >
                            <small class="text-muted">Date actuelle : {{ $blog->publication_date?->format('d/m/Y H:i') ?? 'Non définie' }}</small>
                        </div>
                        <div class="border-top pt-3">
                            <small class="text-muted">Auteur</small>
                            <div class="d-flex align-items-center mt-1">
                                <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:32px;height:32px;">
                                    {{ substr($blog->author->name, 0, 1) }}
                                </span>
                                <span>{{ $blog->author->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">
                    Mettre à jour l'article
                </button>
                <a href="{{ route('blogs.show', $blog) }}" class="btn btn-outline-secondary w-100 mb-2">
                    Annuler
                </a>
                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer définitivement cet article ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        Supprimer l'article
                    </button>
                </form>

                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="card-title">ℹ️ Informations</h6>
                        <ul class="list-unstyled mb-0">
                            <li>Créé le : {{ $blog->created_at->format('d/m/Y à H:i') }}</li>
                            <li>Modifié le : {{ $blog->updated_at->format('d/m/Y à H:i') }}</li>
                            <li>ID de l'article : #{{ $blog->id }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
{{-- Keep your JS logic, but update selectors if needed for Bootstrap --}}
@endpush
@endsection
